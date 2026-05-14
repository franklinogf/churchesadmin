import { CheckIcon, CheckSquareIcon, SquareIcon } from 'lucide-react';

import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { cn } from '@/lib/utils';
import type { ColumnDef } from '@tanstack/react-table';
import { useTranslation } from 'react-i18next';

export const selectionHeader: ColumnDef<unknown> = {
  id: 'select',
  enableHiding: false,
  enableSorting: false,
  header: ({ table }) => (
    <TableSelectionToggleHeader
      allPageSelected={table.getIsAllPageRowsSelected()}
      currentPageSelected={table.getIsSomePageRowsSelected()}
      onSelectCurrentPage={() => table.toggleAllPageRowsSelected(!table.getIsAllPageRowsSelected())}
      onSelectAllPages={() => table.toggleAllRowsSelected(!table.getIsAllRowsSelected())}
      amountOfPages={table.getPageCount()}
    />
  ),
  cell: ({ row }) => (
    <TableSelectionToggleCell
      selected={row.getIsSelected()}
      onSelect={(selected) => row.toggleSelected(selected)}
      canBeSelected={row.getCanSelect()}
    />
  ),
  size: 0,
};

export function TableSelectionToggleHeader({
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
  const { t } = useTranslation('datatable');

  if (amountOfPages <= 1) {
    return <Checkbox className="border-foreground/40" checked={allPageSelected} onCheckedChange={onSelectCurrentPage} aria-label="Select all rows" />;
  }

  return (
    <div className="flex items-center gap-2">
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <Button variant="ghost" size="icon" className={cn('-ml-1 size-6', allPageSelected && 'bg-accent text-accent-foreground')}>
            {allPageSelected ? <CheckSquareIcon className="size-4" /> : <SquareIcon className="size-4" />}
            <span className="sr-only">{t(($) => $.selection.title)}</span>
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent>
          <DropdownMenuItem onClick={onSelectCurrentPage}>
            {currentPageSelected && <CheckIcon className="size-4" />}
            {t(($) => $.selection.selectCurrent)}
          </DropdownMenuItem>
          <DropdownMenuItem onClick={onSelectAllPages}>
            {allPageSelected && <CheckIcon className="size-4" />}
            {t(($) => $.selection.selectAll)}
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
    </div>
  );
}

export function TableSelectionToggleCell({
  selected,
  onSelect,
  canBeSelected,
}: {
  selected: boolean;
  onSelect: (selected: boolean) => void;
  canBeSelected: boolean;
}) {
  return (
    <Checkbox
      className="border-foreground/40"
      disabled={!canBeSelected}
      checked={selected}
      onCheckedChange={(value) => onSelect(!!value)}
      aria-label="Select row"
    />
  );
}
