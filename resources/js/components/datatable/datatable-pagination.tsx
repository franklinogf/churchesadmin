import { router } from '@inertiajs/react';
import type { Table } from '@tanstack/react-table';
import { ChevronLeftIcon, ChevronRightIcon, ChevronsLeftIcon, ChevronsRightIcon } from 'lucide-react';

import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useTranslation } from 'react-i18next';

interface DataTablePaginationProps<TData> {
  table: Table<TData>;
  isSelectable?: boolean;
  pageSizeOptions?: number[];
  hasManualPagination?: boolean;
}

export function DatatablePagination<TData>({
  table,
  isSelectable = false,
  pageSizeOptions,
  hasManualPagination = false,
}: DataTablePaginationProps<TData>) {
  'use no memo';
  const { t } = useTranslation('datatable');
  const pagination = table.getState().pagination;

  return (
    <div className="flex flex-col items-center justify-between gap-3 px-2 sm:flex-row">
      <div className="text-muted-foreground w-full text-center text-sm sm:w-auto sm:text-left">
        {isSelectable
          ? t(($) => $.pagination.totalRowsSelected, {
              selected: String(table.getSelectedRowModel().rows.length),
              count: table.getRowCount(),
            })
          : t(($) => $.pagination.totalRows, {
              count: table.getRowCount(),
            })}
      </div>

      <div className="flex items-center space-x-3 sm:ml-auto sm:space-x-6 lg:space-x-8">
        <div className="flex flex-wrap items-center space-x-2">
          <p className="hidden text-sm font-medium sm:block">{t(($) => $.pagination.rowsPerPage)}</p>

          <Select
            value={pagination.pageSize.toString()}
            onValueChange={(value) => {
              table.setPageSize(Number(value));

              if (hasManualPagination) {
                router.reload({
                  data: { page: 1, perPage: value },
                });
              }
            }}
          >
            <SelectTrigger className="h-8 w-17.5">
              <SelectValue placeholder={pagination.pageSize.toString()} />
            </SelectTrigger>
            <SelectContent side="top">
              {pageSizeOptions?.map((pageSize) => (
                <SelectItem key={pageSize} value={pageSize.toString()}>
                  {pageSize}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>

        <div className="flex w-auto items-center justify-center text-xs font-medium sm:text-sm">
          {t(($) => $.pagination.pageInfo, {
            current: String(pagination.pageIndex + 1),
            total: String(table.getPageCount()),
          })}
        </div>

        <div className="flex items-center space-x-2">
          <Button
            variant="outline"
            className="hidden size-8 p-0 lg:flex"
            onClick={() => {
              table.firstPage();

              if (hasManualPagination) {
                router.reload({ data: { page: 1 } });
              }
            }}
            disabled={!table.getCanPreviousPage()}
          >
            <span className="sr-only">{t(($) => $.pagination.first)}</span>
            <ChevronsLeftIcon />
          </Button>

          <Button
            variant="outline"
            className="size-8 p-0"
            onClick={() => {
              table.previousPage();

              if (hasManualPagination) {
                router.reload({
                  data: { page: table.getState().pagination.pageIndex },
                });
              }
            }}
            disabled={!table.getCanPreviousPage()}
          >
            <span className="sr-only">{t(($) => $.pagination.previous)}</span>
            <ChevronLeftIcon />
          </Button>

          <Button
            variant="outline"
            className="size-8 p-0"
            onClick={() => {
              table.nextPage();

              if (hasManualPagination) {
                router.reload({
                  data: { page: table.getState().pagination.pageIndex + 2 },
                });
              }
            }}
            disabled={!table.getCanNextPage()}
          >
            <span className="sr-only">{t(($) => $.pagination.next)}</span>
            <ChevronRightIcon />
          </Button>

          <Button
            variant="outline"
            className="hidden size-8 p-0 lg:flex"
            onClick={() => {
              table.lastPage();

              if (hasManualPagination) {
                router.reload({ data: { page: table.getPageCount() } });
              }
            }}
            disabled={!table.getCanNextPage()}
          >
            <span className="sr-only">{t(($) => $.pagination.last)}</span>
            <ChevronsRightIcon />
          </Button>
        </div>
      </div>
    </div>
  );
}
