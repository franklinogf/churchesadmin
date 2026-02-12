import { selectionHeader } from '@/components/custom-ui/datatable/columns';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import type { Visit } from '@/types/models/visit';
import type { ColumnDef } from '@tanstack/react-table';

export const columns: ColumnDef<Visit>[] = [
  selectionHeader as ColumnDef<Visit>,
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Name" />,
    accessorKey: 'name',
    enableHiding: false,
    enableColumnFilter: false,
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Last name" />,
    accessorKey: 'lastName',
    enableHiding: false,
    enableColumnFilter: false,
  },
];
