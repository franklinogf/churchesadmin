import { DatatableBadgeCell } from '@/components/custom-ui/datatable/DatatableCell';
import { selectionHeader } from '@/components/datatable/columns';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import type { Member } from '@/types/models/member';
import type { ColumnDef } from '@tanstack/react-table';
import { useTranslation } from 'react-i18next';

export const columns: ColumnDef<Member>[] = [
  selectionHeader as ColumnDef<Member>,
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
  {
    header: ({ column }) => <DatatableHeader column={column} title="Gender" />,
    accessorKey: 'gender',
    filterFn: 'equalsString',
    meta: { filterVariant: 'select', translationPrefix: 'enum:gender.' },
    cell: function CellComponent({ row }) {
      const { t: tEnum } = useTranslation('enum');
      return <DatatableBadgeCell className="w-24">{tEnum(($) => $.gender[row.original.gender])}</DatatableBadgeCell>;
    },
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Civil status" />,
    accessorKey: 'civilStatus',
    filterFn: 'equalsString',
    meta: { filterVariant: 'select', translationPrefix: 'enum:civilStatus.' },
    cell: function CellComponent({ row }) {
      const { t: tEnum } = useTranslation('enum');
      return <DatatableBadgeCell className="w-24">{tEnum(($) => $.civilStatus[row.original.civilStatus])}</DatatableBadgeCell>;
    },
  },
];
