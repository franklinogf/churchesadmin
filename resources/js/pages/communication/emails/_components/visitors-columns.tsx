import { selectionHeader } from '@/components/datatable/columns';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import type { Visit } from '@/types/models/visit';
import type { ColumnDef } from '@tanstack/react-table';

export const columns: ColumnDef<Visit>[] = [
  selectionHeader as ColumnDef<Visit>,
  {
    header: ({ column }) => <DatatableHeader column={column} title="Name" />,
    accessorKey: 'name',
    enableHiding: false,
    enableColumnFilter: false,
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Last name" />,
    accessorKey: 'lastName',
    enableHiding: false,
    enableColumnFilter: false,
  },
];
