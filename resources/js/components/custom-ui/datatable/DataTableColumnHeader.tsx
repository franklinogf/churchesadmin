import { type Column } from '@tanstack/react-table';
import { ArrowDown, ArrowUp, ChevronsUpDown } from 'lucide-react';

import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';

import { useTranslations } from '@/hooks/use-translations';
import { cn } from '@/lib/utils';

interface DataTableColumnHeaderProps<TData, TValue> extends React.HTMLAttributes<HTMLDivElement> {
  column: Column<TData, TValue>;
  title: string;
  center?: boolean;
}

export function DataTableColumnHeader<TData, TValue>({ column, title, className, center = true }: DataTableColumnHeaderProps<TData, TValue>) {
  const { t } = useTranslations<string>();
  if (!column.getCanSort()) {
    return <div className={cn('text-foreground', { 'text-center': center }, className)}>{t(title)}</div>;
  }

  return (
    <div className={cn('flex items-center space-x-2', { 'justify-center': center }, className)}>
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <Button variant="ghost" size="sm" className="text-foreground data-[state=open]:bg-accent data-[state=open]:text-accent-foreground h-8">
            <span>{t(title)}</span>
            {column.getIsSorted() === 'desc' ? (
              <ArrowDown className="size-4" />
            ) : column.getIsSorted() === 'asc' ? (
              <ArrowUp className="size-4" />
            ) : (
              <ChevronsUpDown className="size-4" />
            )}
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="start">
          <DropdownMenuItem onClick={() => column.toggleSorting(false)}>
            <ArrowUp className="text-muted-foreground/70 size-3.5" />
            {t('Asc')}
          </DropdownMenuItem>
          <DropdownMenuItem onClick={() => column.toggleSorting(true)}>
            <ArrowDown className="text-muted-foreground/70 size-3.5" />
            {t('Desc')}
          </DropdownMenuItem>
          {/* <DropdownMenuSeparator /> */}
          {/* <DropdownMenuItem onClick={() => column.toggleVisibility(false)}>
            <EyeOff className="text-muted-foreground/70 h-3.5 w-3.5" />
            Hide
          </DropdownMenuItem> */}
        </DropdownMenuContent>
      </DropdownMenu>
    </div>
  );
}
