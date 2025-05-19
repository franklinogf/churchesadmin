import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import type { ColumnDef, RowData } from '@tanstack/react-table';

export const selectionHeader: ColumnDef<RowData> = {
  id: 'select',
  header: ({ table }) => (
    <div className="flex flex-col gap-y-2">
      <Label className="flex items-center gap-x-1.5">
        <Checkbox
          className="border-foreground/40 bg-primary-foreground data-[state=checked]:bg-primary-foreground data-[state=checked]:text-brand"
          checked={table.getIsAllRowsSelected() || (table.getIsSomeRowsSelected() && 'indeterminate')}
          onCheckedChange={(value) => table.toggleAllRowsSelected(!!value)}
          aria-label="Select all"
        />
        <span>All</span>
      </Label>
      <Label className="flex items-center gap-x-1.5">
        <Checkbox
          className="border-foreground/40 bg-primary-foreground data-[state=checked]:bg-primary-foreground data-[state=checked]:text-brand"
          checked={table.getIsAllPageRowsSelected() || (table.getIsSomePageRowsSelected() && 'indeterminate')}
          onCheckedChange={(value) => table.toggleAllPageRowsSelected(!!value)}
          aria-label="Select all in page"
        />
        <span>All page</span>
      </Label>
    </div>
  ),

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
};
