import type { Column } from '@tanstack/react-table';
import type { VariantProps } from 'class-variance-authority';
import { cva } from 'class-variance-authority';
import { ArrowDown, ArrowUp, ChevronsUpDown, EyeOffIcon } from 'lucide-react';

import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { cn } from '@/lib/utils';
import { useTranslation } from 'react-i18next';

export const headerVariants = cva('text', {
  variants: {
    align: {
      start: 'justify-start text-left',
      end: 'justify-end text-right',
      center: 'justify-center text-center',
    },
  },
  defaultVariants: {
    align: 'start',
  },
});

type DataTableColumnHeaderProps<TData, TValue> = VariantProps<typeof headerVariants> & {
  column: Column<TData, TValue>;
  title: string;
  className?: string;
  justify?: VariantProps<typeof headerVariants>['align'];
};

export function DatatableHeader<TData, TValue>({ column, title, align = 'start', justify, className }: DataTableColumnHeaderProps<TData, TValue>) {
  'use no memo';
  const { t } = useTranslation('datatable');
  const headerAlign = justify ?? align;

  if (!column.getCanSort() && !column.getCanHide()) {
    return <div className={headerVariants({ align: headerAlign, className })}>{title}</div>;
  }

  return (
    <div className={cn('flex items-center', headerVariants({ align: headerAlign, className }))}>
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <Button variant="ghost" size="sm" className="data-[state=open]:bg-accent data-[state=open]:text-accent-foreground h-6 px-1.5">
            <span>{title}</span>
            {column.getCanSort() && (
              <>
                {column.getIsSorted() === 'desc' && <ArrowDown className="size-3" />}
                {column.getIsSorted() === 'asc' && <ArrowUp className="size-3" />}
                {column.getIsSorted() === false && <ChevronsUpDown className="size-3" />}
              </>
            )}
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="start">
          {column.getCanSort() && (
            <>
              {column.getIsSorted() !== 'asc' && (
                <DropdownMenuItem onSelect={() => column.toggleSorting(false)}>
                  <ArrowUp className="text-muted-foreground/70 size-3.5" />
                  {t(($) => $.buttons.sortAsc)}
                </DropdownMenuItem>
              )}
              {column.getIsSorted() !== 'desc' && (
                <DropdownMenuItem onSelect={() => column.toggleSorting(true)}>
                  <ArrowDown className="text-muted-foreground/70 size-3.5" />
                  {t(($) => $.buttons.sortDesc)}
                </DropdownMenuItem>
              )}
            </>
          )}
          {column.getCanHide() && (
            <>
              {column.getCanSort() && <DropdownMenuSeparator />}
              <DropdownMenuItem onSelect={() => column.toggleVisibility(false)}>
                <EyeOffIcon className="text-muted-foreground/70 size-3.5" />
                {t(($) => $.buttons.hideColumn)}
              </DropdownMenuItem>
            </>
          )}
        </DropdownMenuContent>
      </DropdownMenu>
    </div>
  );
}
