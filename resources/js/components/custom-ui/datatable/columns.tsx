import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useTranslations } from '@/hooks/use-translations';
import { cn } from '@/lib/utils';
import type { ColumnDef, RowData } from '@tanstack/react-table';
import { CheckIcon, CheckSquareIcon, SquareIcon } from 'lucide-react';

export const selectionHeader: ColumnDef<RowData> = {
  id: 'select',
  size: 0,
  header: function HeaderComponent({ table }) {
    return (
      <TableSelectionToggle
        amountOfPages={table.getPageCount()}
        allPageSelected={table.getIsAllRowsSelected()}
        onSelectAllPages={() => table.toggleAllRowsSelected(!table.getIsAllRowsSelected())}
        currentPageSelected={table.getIsAllPageRowsSelected()}
        onSelectCurrentPage={() => table.toggleAllRowsSelected(!table.getIsAllPageRowsSelected())}
      />
    );
  },

  cell: ({ row }) => (
    <Checkbox
      className="border-foreground/40"
      checked={row.getIsSelected()}
      onCheckedChange={(value) => row.toggleSelected(!!value)}
      aria-label="Select row"
    />
  ),
  enableSorting: false,
  enableHiding: false,
  enableColumnFilter: false,
};

export function TableSelectionToggle({
  allPageSelected,
  currentPageSelected,
  onSelectCurrentPage,
  onSelectAllPages,
  amountOfPages,
}: {
  allPageSelected: boolean;
  currentPageSelected: boolean;
  onSelectCurrentPage: () => void;
  onSelectAllPages: () => void;
  amountOfPages: number;
}) {
  const { t } = useTranslations();
  if (amountOfPages <= 1) {
    return <Checkbox className="border-foreground/40" checked={allPageSelected} onCheckedChange={onSelectCurrentPage} aria-label="Select all rows" />;
  }
  return (
    <div className="flex items-center gap-2">
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <Button variant="ghost" size="icon" className={cn('-ml-1 size-6', allPageSelected && 'bg-accent text-accent-foreground')}>
            {allPageSelected ? <CheckSquareIcon className="size-4" /> : <SquareIcon className="size-4" />}
            <span className="sr-only">{t('datatable.select.title')}</span>
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent>
          <DropdownMenuItem onClick={onSelectCurrentPage}>
            {currentPageSelected && <CheckIcon className="size-4" />}
            {t('datatable.select.current_page')}
          </DropdownMenuItem>
          <DropdownMenuItem onClick={onSelectAllPages}>
            {allPageSelected && <CheckIcon className="size-4" />}
            {t('datatable.select.all_pages')}
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
    </div>
  );
}
