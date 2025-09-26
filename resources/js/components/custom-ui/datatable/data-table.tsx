import { DataTablePagination } from '@/components/custom-ui/datatable/DataTablePagination';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuRadioGroup,
  DropdownMenuRadioItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useTranslations } from '@/hooks/use-translations';
import type { TranslationKey } from '@/types/lang-keys';
import {
  type Column,
  type ColumnDef,
  type ColumnFiltersState,
  flexRender,
  getCoreRowModel,
  getFacetedMinMaxValues,
  getFacetedRowModel,
  getFacetedUniqueValues,
  getFilteredRowModel,
  getPaginationRowModel,
  getSortedRowModel,
  type HeaderContext,
  type SortingState,
  useReactTable,
  type VisibilityState,
} from '@tanstack/react-table';
import { FilterIcon, PrinterIcon, Settings2Icon } from 'lucide-react';
import { useEffect, useMemo, useRef, useState } from 'react';
import { toast } from 'sonner';

type DataTableProps<TData> = {
  columns: ColumnDef<TData>[];
  data: TData[];
  onButtonClick?: (data: TData[]) => void;
  selectedActionButtonLabel?: string;
  selectOne?: boolean;
  rowId?: keyof TData;
  headerButton?: React.ReactNode;
  visibilityState?: Record<keyof TData, boolean> | VisibilityState;
  filteringState?: ColumnFiltersState;
  sortingState?: { id: keyof TData; desc: boolean }[];
  onSelectedRowsChange?: (selectedRows: string[]) => void;
  onSelectedRowsChangeOriginal?: (selectedRows: TData[]) => void;
  print?: (selectedRows: string[]) => void;
  printWithOriginalData?: (selectedRows: TData[]) => void;
};

export function DataTable<TData>({
  columns,
  data,
  onButtonClick,
  selectedActionButtonLabel,
  selectOne = false,
  rowId,
  headerButton,
  visibilityState = {},
  sortingState = [],
  filteringState = [],
  onSelectedRowsChange,
  onSelectedRowsChangeOriginal,
  print,
  printWithOriginalData,
}: DataTableProps<TData>) {
  const { t } = useTranslations();
  const [sorting, setSorting] = useState<SortingState>(sortingState as SortingState);

  const [rowSelection, setRowSelection] = useState({});
  const [columnVisibility, setColumnVisibility] = useState<VisibilityState>(visibilityState);
  const [columnFilters, setColumnFilters] = useState<ColumnFiltersState>(filteringState);
  const lastEmitted = useRef<string[] | TData[]>([]);

  const table = useReactTable({
    columns,
    data,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    onSortingChange: setSorting,
    getFilteredRowModel: getFilteredRowModel(),
    getFacetedRowModel: getFacetedRowModel(),
    getFacetedUniqueValues: getFacetedUniqueValues(),
    getFacetedMinMaxValues: getFacetedMinMaxValues(),
    onRowSelectionChange: setRowSelection,
    enableMultiRowSelection: !selectOne,
    onColumnVisibilityChange: setColumnVisibility,
    onColumnFiltersChange: setColumnFilters,
    state: { sorting, rowSelection, columnVisibility, columnFilters },
    getRowId: rowId ? (row: TData) => row[rowId as keyof TData] as string : undefined,
  });

  const tableColumns = table.getAllColumns();
  const enabledHidingColumns = tableColumns.filter((column) => column.getCanHide());
  const canSelect = tableColumns.some((col) => col.id === 'select');

  const tableHeaderGroups = table.getHeaderGroups();
  const tableSelectFilters = table
    .getAllColumns()
    .flatMap((column) => (column.getCanFilter() && column.columnDef.meta?.filterVariant === 'select' ? [column] : []));

  useEffect(() => {
    if (!onSelectedRowsChange && !onSelectedRowsChangeOriginal) return;
    const current = Object.keys(rowSelection);
    const same = current.length === lastEmitted.current.length && current.every((id, i) => id === lastEmitted.current[i]);

    if (!same) {
      lastEmitted.current = current;
      onSelectedRowsChange?.(current);
      onSelectedRowsChangeOriginal?.(table.getSelectedRowModel().flatRows.map((row) => row.original));
    }
  }, [rowSelection, onSelectedRowsChange, onSelectedRowsChangeOriginal, table]);

  return (
    <div>
      <section className="flex items-center justify-between py-2">
        <div className="flex items-center gap-2">
          {headerButton}
          {tableSelectFilters.length > 0 && (
            <Popover>
              <PopoverTrigger asChild>
                <Button variant="outline" size="sm">
                  <FilterIcon className="size-4" />
                  {t('datatable.filter_button')}
                </Button>
              </PopoverTrigger>
              <PopoverContent>
                <div className="flex flex-col gap-1">
                  {tableSelectFilters.map((column) => (
                    <ColumnSelectFilter key={column.id} column={column} />
                  ))}
                </div>
              </PopoverContent>
            </Popover>
          )}
        </div>

        <div className="ml-auto flex items-center gap-2">
          {(print || printWithOriginalData) && (
            <Button
              variant="outline"
              size="sm"
              onClick={() => {
                print?.(Object.keys(rowSelection));
                printWithOriginalData?.(table.getSelectedRowModel().flatRows.map((row) => row.original));
              }}
            >
              <PrinterIcon className="size-4" />
              {t('datatable.print_button')}
            </Button>
          )}
          <VisibilityDropdownMenu label={t('datatable.visibility_button')} columns={enabledHidingColumns} />
        </div>
      </section>
      <section className="rounded-md border">
        <Table>
          <TableHeader>
            {tableHeaderGroups.map((headerGroup) => (
              <TableRow key={headerGroup.id} className="hover:bg-background">
                {headerGroup.headers.map((header) => (
                  <TableHead
                    className="py-1"
                    colSpan={header.colSpan}
                    rowSpan={header.rowSpan}
                    key={header.id}
                    style={{ width: `${header.getSize()}px` }}
                  >
                    {header.isPlaceholder ? null : flexRender(header.column.columnDef.header, header.getContext())}
                  </TableHead>
                ))}
              </TableRow>
            ))}
          </TableHeader>
          <TableBody className="bg-background/80">
            {table.getRowModel().rows?.length ? (
              table.getRowModel().rows.map((row) => (
                <TableRow key={row.id} data-state={row.getIsSelected() && 'selected'}>
                  {row.getVisibleCells().map((cell) => (
                    <TableCell className="p-2" key={cell.id}>
                      {flexRender(cell.column.columnDef.cell, cell.getContext())}
                    </TableCell>
                  ))}
                </TableRow>
              ))
            ) : (
              <TableRow>
                <TableCell colSpan={columns.length} className="h-24 text-center">
                  {t('datatable.empty')}
                </TableCell>
              </TableRow>
            )}
          </TableBody>
        </Table>
      </section>
      <section className="mt-1">
        <DataTablePagination isSelectable={canSelect} table={table} />
      </section>
      {canSelect && selectedActionButtonLabel && (
        <section className="mt-4 flex justify-center">
          <Button
            className="cursor-pointer"
            onClick={() => {
              if (table.getSelectedRowModel().rows.length === 0) {
                toast.info(t('datatable.no_selected_rows'));
              } else {
                onButtonClick?.(table.getSelectedRowModel().flatRows.map((row) => row.original));
              }
            }}
          >
            {selectedActionButtonLabel}
          </Button>
        </section>
      )}
    </div>
  );
}

function getColumnLabel<TData>(column: Column<TData, unknown>): string {
  if (typeof column.columnDef.header === 'function') {
    const headerNode = column.columnDef.header({ column } as HeaderContext<TData, unknown>);
    return headerNode?.props?.title ?? column.id;
  }

  return column.id;
}

function VisibilityDropdownMenu<TData>({
  columns,
  label,
}: {
  columns: Column<TData, unknown>[];
  label: string;
  itemLabelFunction?: (label: string) => string;
}) {
  if (columns.length === 0) return null;
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

function ColumnSelectFilter<TData, TValue>({ column }: { column: Column<TData, TValue> }) {
  const { t } = useTranslations();
  const columnFilterValue = column.getFilterValue() ?? 'all';
  const { translationPrefix } = column.columnDef.meta ?? {};

  const sortedUniqueValues = useMemo(() => Array.from(column.getFacetedUniqueValues().keys()).sort().slice(0, 5000), [column]);
  return (
    <DropdownMenu>
      <DropdownMenuTrigger asChild>
        <Button variant="outline" size="sm">
          <div className="flex w-full items-center justify-between gap-2">
            <span>{getColumnLabel(column)}</span>
            {columnFilterValue !== 'all' && (
              <Badge variant="secondary">{t(`${translationPrefix || ''}${columnFilterValue}` as TranslationKey)}</Badge>
            )}
          </div>
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent>
        <DropdownMenuRadioGroup onValueChange={(value) => column.setFilterValue(value === 'all' ? '' : value)} value={columnFilterValue?.toString()}>
          <DropdownMenuRadioItem value="all">{t('datatable.unselect_filter')}</DropdownMenuRadioItem>
          {sortedUniqueValues.map((value) => (
            <DropdownMenuRadioItem key={value} value={value}>
              {t(`${translationPrefix || ''}${value}` as TranslationKey)}
            </DropdownMenuRadioItem>
          ))}
        </DropdownMenuRadioGroup>
      </DropdownMenuContent>
    </DropdownMenu>
  );
}
