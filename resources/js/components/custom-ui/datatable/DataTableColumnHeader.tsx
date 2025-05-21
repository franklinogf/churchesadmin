import { type Column } from '@tanstack/react-table';
import { ArrowDown, ArrowUp, ChevronsUpDown, EyeOffIcon } from 'lucide-react';

import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';

import { useTranslations } from '@/hooks/use-translations';
import { cn } from '@/lib/utils';

interface DataTableColumnHeaderProps<TData, TValue> extends React.HTMLAttributes<HTMLDivElement> {
  column: Column<TData, TValue>;
  title: string;
  center?: boolean;
}

export function DataTableColumnHeader<TData, TValue>({ column, title, className, center = true }: DataTableColumnHeaderProps<TData, TValue>) {
  const { t } = useTranslations();

  if (!column.getCanSort() && !column.getCanHide()) {
    return <div className={cn('text-foreground', { 'text-center': center }, className)}>{title}</div>;
  }

  return (
    <div className={cn('flex flex-col items-center', { 'justify-center': center }, className)}>
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <Button variant="ghost" size="sm" className="text-foreground data-[state=open]:bg-accent data-[state=open]:text-accent-foreground h-8">
            <span>{title}</span>
            {column.getCanSort() &&
              (column.getIsSorted() === 'desc' ? (
                <ArrowDown className="size-4" />
              ) : column.getIsSorted() === 'asc' ? (
                <ArrowUp className="size-4" />
              ) : (
                <ChevronsUpDown className="size-4" />
              ))}
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="start">
          {column.getCanSort() && (
            <>
              <DropdownMenuItem onClick={() => column.toggleSorting(false)}>
                <ArrowUp className="text-muted-foreground/70 size-3.5" />
                {t('datatable.sort.ascending')}
              </DropdownMenuItem>
              <DropdownMenuItem onClick={() => column.toggleSorting(true)}>
                <ArrowDown className="text-muted-foreground/70 size-3.5" />
                {t('datatable.sort.descending')}
              </DropdownMenuItem>
            </>
          )}
          {column.getCanHide() && (
            <>
              {column.getCanSort() && <DropdownMenuSeparator />}
              <DropdownMenuItem onClick={() => column.toggleVisibility(false)}>
                <EyeOffIcon className="text-muted-foreground/70 size-3.5" />
                {t('datatable.hide')}
              </DropdownMenuItem>
            </>
          )}
        </DropdownMenuContent>
      </DropdownMenu>
    </div>
  );
}
