import { DataTablePagination } from '@/components/custom-ui/datatable/DataTablePagination';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useTranslations } from '@/hooks/use-translations';
import type { TranslationKey } from '@/types/lang-keys';
import {
  type Column,
  type ColumnDef,
  flexRender,
  getCoreRowModel,
  getFilteredRowModel,
  getPaginationRowModel,
  getSortedRowModel,
  type HeaderContext,
  type SortingState,
  useReactTable,
  type VisibilityState,
} from '@tanstack/react-table';
import { XSquare } from 'lucide-react';
import { useEffect, useState } from 'react';
import { toast } from 'sonner';

interface DataTableProps<TData, TValue> {
  columns: ColumnDef<TData, TValue>[];
  data: TData[];
  onButtonClick?: (data: TData[]) => void;
  selectedActionButtonLabel?: string;
  filter?: boolean;
  selectOne?: boolean;
  rowId?: keyof TData;
  headerButton?: React.ReactNode;
  visibilityState?: Record<keyof TData, boolean> | VisibilityState;
  sortingState?: { id: keyof TData; desc: boolean }[];
  onSelectedRowsChange?: (selectedRows: Record<string, boolean>) => void;
}

export function DataTable<TData, TValue>({
  columns,
  data,
  onButtonClick,
  selectedActionButtonLabel,
  filter = true,
  selectOne = false,
  rowId,
  headerButton,
  visibilityState = {},
  sortingState = [],

  onSelectedRowsChange,
}: DataTableProps<TData, TValue>) {
  const [sorting, setSorting] = useState<SortingState>(sortingState as SortingState);
  const [globalFilter, setGlobalFilter] = useState<string>('');
  const [rowSelection, setRowSelection] = useState({});
  const [columnVisibility, setColumnVisibility] = useState<VisibilityState>(visibilityState);
  const table = useReactTable({
    columns,
    data,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    onSortingChange: setSorting,
    onGlobalFilterChange: setGlobalFilter,
    getFilteredRowModel: getFilteredRowModel(),
    globalFilterFn: 'includesString',
    onRowSelectionChange: setRowSelection,
    enableMultiRowSelection: !selectOne,
    onColumnVisibilityChange: setColumnVisibility,
    state: { sorting, globalFilter, rowSelection, columnVisibility },
    getRowId: rowId ? (row: TData) => row[rowId as keyof TData] as string : undefined,
  });

  useEffect(() => {
    if (!onSelectedRowsChange) return;
    onSelectedRowsChange(rowSelection);
  }, [rowSelection, onSelectedRowsChange]);

  const { t } = useTranslations();
  const tableColumns = table.getAllColumns();
  const enabledHidingColumns = tableColumns.filter((column) => column.getCanHide());
  const canSelect = tableColumns.some((col) => col.id === 'select');
  return (
    <div>
      <div className="flex items-center justify-between py-2">
        <div className="flex items-center gap-2">
          {headerButton}
          {filter && (
            <div className="relative mr-auto">
              <Input
                name="datatable-filter"
                placeholder={t('datatable.filter')}
                value={globalFilter}
                onChange={(e) => {
                  table.setGlobalFilter(e.target.value);
                }}
              />
              {globalFilter && (
                <Button
                  onClick={() => {
                    table.setGlobalFilter('');
                  }}
                  asChild
                  className="size-4"
                  size="icon"
                  variant="ghost"
                >
                  <XSquare className="hover:text-primary absolute top-1/2 right-2 -translate-y-1/2 hover:cursor-pointer" />
                </Button>
              )}
            </div>
          )}
        </div>

        <div className="ml-auto">
          <VisibilityDropdownMenu columns={enabledHidingColumns} />
        </div>
      </div>
      <div className="rounded-md border">
        <Table>
          <TableHeader>
            {table.getHeaderGroups().map((headerGroup) => (
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
      </div>
      <div className="mt-1">
        <DataTablePagination isSelectable={canSelect} table={table} />
      </div>
      {canSelect && selectedActionButtonLabel && (
        <div className="mt-4 flex justify-center">
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
        </div>
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

function VisibilityDropdownMenu<TData>({ columns }: { columns: Column<TData, unknown>[] }) {
  const { t } = useTranslations();
  if (columns.length === 0) return null;
  return (
    <DropdownMenu>
      <DropdownMenuTrigger asChild>
        <Button variant="outline" className="ml-auto">
          {t('datatable.visibility_button')}
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="end">
        {columns.map((column) => {
          return (
            <DropdownMenuCheckboxItem key={column.id} checked={column.getIsVisible()} onCheckedChange={(value) => column.toggleVisibility(!!value)}>
              {/* {t(column.columnDef?.meta?.toString() as TranslationKey) || column.id.replaceAll('_', ' ')} */}
              {t(getColumnLabel(column) as TranslationKey)}
            </DropdownMenuCheckboxItem>
          );
        })}
      </DropdownMenuContent>
    </DropdownMenu>
  );
}
