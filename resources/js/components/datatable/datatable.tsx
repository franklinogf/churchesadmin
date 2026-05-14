import type {
  Column,
  ColumnDef,
  ColumnFiltersState,
  Row,
  RowSelectionState,
  SortingState,
  Table as TanStackTable,
  VisibilityState,
} from '@tanstack/react-table';
import {
  flexRender,
  getCoreRowModel,
  getExpandedRowModel,
  getFacetedMinMaxValues,
  getFacetedRowModel,
  getFacetedUniqueValues,
  getFilteredRowModel,
  getPaginationRowModel,
  getSortedRowModel,
  useReactTable,
} from '@tanstack/react-table';
import { ChevronDownIcon, ChevronRightIcon, Settings2Icon } from 'lucide-react';
import type { ReactElement, ReactNode } from 'react';
import { Fragment, useEffect, useMemo, useState } from 'react';

import { TableSelectionToggleCell, TableSelectionToggleHeader } from '@/components/datatable/columns';
import { DatatablePagination } from '@/components/datatable/datatable-pagination';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import type { PaginatedModel } from '@/types/models';
import { useTranslation } from 'react-i18next';

export type StringOrNumberKeyOf<T> = Exclude<{ [K in keyof T]: T[K] extends string | number ? K : never }[keyof T], undefined>;

type DataTableProps<TData> = {
  columns: ColumnDef<TData>[];
  data: TData[] | PaginatedModel<TData>;
  selectOne?: boolean;
  rowId?: StringOrNumberKeyOf<TData>;
  visibilityState?: VisibilityState;
  filteringState?: ColumnFiltersState;
  sortingState?: SortingState;
  noPagination?: boolean;
  selectable?: boolean;
  paginationPageSizes?: number[];
  emptyText?: string;
  renderLeftTop?: ReactNode | ((table: TanStackTable<TData>) => ReactElement);
  renderRightTop?: ReactNode | ((table: TanStackTable<TData>) => ReactElement);
  renderSubComponent?: (props: { row: Row<TData> }) => ReactElement;
  onTableInstance?: (table: TanStackTable<TData>) => void;
  onRowSelection?: (rows: string[]) => void;
  onSelectedRowsChange?: (rows: string[]) => void;
  canBeSelected?: (row: Row<TData>) => boolean;
};

export function Datatable<TData>({
  columns,
  data,
  selectOne = false,
  rowId,
  visibilityState,
  sortingState = [],
  filteringState = [],
  noPagination = false,
  selectable = false,
  paginationPageSizes = [10, 20, 30, 40, 50],
  emptyText,
  renderLeftTop,
  renderRightTop,
  renderSubComponent,
  onTableInstance,
  onRowSelection,
  onSelectedRowsChange,
  canBeSelected,
}: DataTableProps<TData>) {
  'use no memo';
  const { t } = useTranslation('datatable');
  const [sorting, setSorting] = useState<SortingState>(sortingState);
  const [rowSelection, setRowSelection] = useState<RowSelectionState>({});
  const [columnVisibility, setColumnVisibility] = useState<VisibilityState>(visibilityState || {});
  const [columnFilters, setColumnFilters] = useState<ColumnFiltersState>(filteringState);
  const hasManualPagination = 'data' in data;

  //   const [pagination, setPagination] = useState<PaginationState>({
  //     pageIndex: 0, //initial page index
  //     pageSize: paginationPageSizes?.[0], //default page size
  //   });

  const canBeExpanded = !!renderSubComponent;

  const table = useReactTable({
    columns,
    data: hasManualPagination ? data.data : data,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getFacetedRowModel: getFacetedRowModel(),
    getFacetedUniqueValues: getFacetedUniqueValues(),
    getFacetedMinMaxValues: getFacetedMinMaxValues(),
    getExpandedRowModel: getExpandedRowModel(),
    getRowCanExpand: () => canBeExpanded,
    onSortingChange: setSorting,
    onRowSelectionChange: setRowSelection,
    onColumnVisibilityChange: setColumnVisibility,
    onColumnFiltersChange: setColumnFilters,
    state: {
      sorting,
      rowSelection,
      columnVisibility,
      columnFilters,
    },
    initialState: {
      pagination: {
        pageIndex: 0,
        pageSize: hasManualPagination ? data.meta.per_page : paginationPageSizes?.[0],
      },
    },
    getRowId: rowId ? (row) => row[rowId] as string : undefined,
    //change defaults
    paginateExpandedRows: false,
    enableRowSelection: (row) => canBeSelected?.(row) ?? selectable,
    enableMultiRowSelection: !selectOne,
    manualPagination: hasManualPagination,
    rowCount: hasManualPagination ? data.meta.total : undefined,
  });

  const enabledHidingColumns = useMemo(() => table.getAllColumns().filter((column) => column.getCanHide()), [table]);

  const tableHeaderGroups = table.getHeaderGroups();
  const tableSelectFilters = useMemo(
    () => table.getAllColumns().flatMap((column) => (column.getCanFilter() && column.columnDef.meta?.filter?.variant === 'select' ? [column] : [])),
    [table],
  );

  useEffect(() => {
    onTableInstance?.(table);
  }, [table, onTableInstance]);

  useEffect(() => {
    onRowSelection?.(Object.keys(rowSelection));
    onSelectedRowsChange?.(Object.keys(rowSelection));
    // onRowSelection is intentionally excluded — callers pass inline functions and
    // we only want this to fire when the selection state actually changes.
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [rowSelection]);

  return (
    <div className="flex w-full flex-col gap-0.5">
      <section className="flex items-center justify-between py-2">
        <div className="flex items-center gap-2">
          {typeof renderLeftTop === 'function' ? renderLeftTop(table) : renderLeftTop}
          {tableSelectFilters.map((column) => (
            <ColumnSelectFilter key={column.id} column={column} />
          ))}
        </div>

        <div className="ml-auto flex items-center gap-2">
          <VisibilityDropdownMenu label={t(($) => $.buttons.visibility)} columns={enabledHidingColumns} />
          {typeof renderRightTop === 'function' ? renderRightTop(table) : renderRightTop}
        </div>
      </section>
      <section className="rounded-md border">
        <Table>
          <TableHeader className="bg-muted/80 text-muted-foreground">
            {tableHeaderGroups.map((headerGroup) => (
              <TableRow key={headerGroup.id}>
                {selectable && (
                  <TableHead className="w-0">
                    <TableSelectionToggleHeader
                      amountOfPages={table.getPageCount()}
                      allPageSelected={table.getIsAllRowsSelected()}
                      onSelectAllPages={() => table.toggleAllRowsSelected(!table.getIsAllRowsSelected())}
                      currentPageSelected={table.getIsAllPageRowsSelected()}
                      onSelectCurrentPage={() => table.toggleAllPageRowsSelected(!table.getIsAllPageRowsSelected())}
                    />
                  </TableHead>
                )}
                {canBeExpanded && <TableHead className="w-0" />}
                {headerGroup.headers.map((header) => (
                  <TableHead
                    className="py-1"
                    colSpan={header.colSpan}
                    rowSpan={header.rowSpan}
                    key={header.id}
                    style={{ width: `${header.getSize()}px` }}
                  >
                    <div>
                      {header.isPlaceholder ? null : flexRender(header.column.columnDef.header, header.getContext())}
                      {header.column.getCanFilter() && header.column.columnDef.meta?.filter?.variant === 'text' && (
                        <Input
                          className="h-6 max-w-60 px-2 py-0 text-xs"
                          value={(header.column.getFilterValue() ?? '') as string}
                          onChange={(e) => header.column.setFilterValue(e.target.value)}
                        />
                      )}
                    </div>
                  </TableHead>
                ))}
              </TableRow>
            ))}
          </TableHeader>
          <TableBody className="bg-background/80">
            {table.getRowModel().rows?.length ? (
              table.getRowModel().rows.map((row) => (
                <Fragment key={row.id}>
                  <TableRow data-state={row.getIsSelected() && 'selected'}>
                    {selectable && (
                      <TableCell className="w-0">
                        <TableSelectionToggleCell
                          selected={row.getIsSelected()}
                          onSelect={(selected) => row.toggleSelected(selected)}
                          canBeSelected={row.getCanSelect()}
                        />
                      </TableCell>
                    )}
                    {canBeExpanded && (
                      <TableCell className="w-0">
                        {row.getCanExpand() ? (
                          <Button variant="ghost" size="icon" onClick={row.getToggleExpandedHandler()} className="cursor-pointer">
                            {row.getIsExpanded() ? <ChevronDownIcon /> : <ChevronRightIcon />}
                          </Button>
                        ) : null}
                      </TableCell>
                    )}
                    {row.getVisibleCells().map((cell) => (
                      <TableCell key={cell.id}>{flexRender(cell.column.columnDef.cell, cell.getContext())}</TableCell>
                    ))}
                  </TableRow>
                  {row.getIsExpanded() && (
                    <TableRow>
                      <TableCell colSpan={row.getVisibleCells().length + (selectable ? 1 : 0) + (canBeExpanded ? 1 : 0)} className="bg-accent/10">
                        {renderSubComponent?.({ row })}
                      </TableCell>
                    </TableRow>
                  )}
                </Fragment>
              ))
            ) : (
              <TableRow>
                <TableCell
                  colSpan={table.getVisibleLeafColumns().length + (selectable ? 1 : 0) + (canBeExpanded ? 1 : 0)}
                  className="h-24 text-center"
                >
                  {emptyText || t(($) => $.state.empty)}
                </TableCell>
              </TableRow>
            )}
          </TableBody>
        </Table>
      </section>
      {!noPagination && (
        <section className="mt-1">
          <DatatablePagination
            hasManualPagination={hasManualPagination}
            pageSizeOptions={paginationPageSizes}
            isSelectable={selectable}
            table={table}
          />
        </section>
      )}
    </div>
  );
}

export default Datatable;

function getColumnLabel<TData>(column: Column<TData, unknown>): string {
  if (column.columnDef.meta?.label) {
    return column.columnDef.meta.label;
  }

  if (typeof column.columnDef.header === 'string') {
    return column.columnDef.header;
  }

  return column.id;
}

function VisibilityDropdownMenu<TData>({ columns, label }: { columns: Column<TData, unknown>[]; label: string }) {
  if (columns.length === 0) {
    return null;
  }

  return (
    <DropdownMenu>
      <DropdownMenuTrigger asChild>
        <Button variant="outline" size="sm" className="ml-auto">
          <Settings2Icon className="size-4" />
          {label}
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="end">
        {columns.map((column) => {
          return (
            <DropdownMenuCheckboxItem key={column.id} checked={column.getIsVisible()} onCheckedChange={(value) => column.toggleVisibility(!!value)}>
              {getColumnLabel(column)}
            </DropdownMenuCheckboxItem>
          );
        })}
      </DropdownMenuContent>
    </DropdownMenu>
  );
}

function ColumnSelectFilter<TData>({ column }: { column: Column<TData, unknown> }) {
  const { t } = useTranslation('datatable');
  const filterValue = (column.getFilterValue() as string) ?? '';
  const meta = column.columnDef.meta?.filter;
  const explicitOptions = meta?.variant === 'select' ? meta.options : undefined;
  const options = useMemo(
    () =>
      explicitOptions ??
      Array.from(column.getFacetedUniqueValues().keys())
        .sort()
        .slice(0, 5000)
        .map((v) => ({ value: String(v), label: String(v) })),
    [column, explicitOptions],
  );

  return (
    <Select value={filterValue} onValueChange={(value) => column.setFilterValue(value || undefined)}>
      <SelectTrigger className="h-8 min-w-32 text-sm">
        <SelectValue placeholder={getColumnLabel(column)} />
      </SelectTrigger>
      <SelectContent>
        <SelectItem value="">{t(($) => $.buttons.unFilter)}</SelectItem>
        {options.map(({ value, label }) => (
          <SelectItem key={value} value={value}>
            {label}
          </SelectItem>
        ))}
      </SelectContent>
    </Select>
  );
}
