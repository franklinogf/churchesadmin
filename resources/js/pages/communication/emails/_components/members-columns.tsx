import { selectionHeader } from '@/components/custom-ui/datatable/columns';
import { DatatableBadgeCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { useTranslations } from '@/hooks/use-translations';
import type { Member } from '@/types/models/member';
import type { ColumnDef } from '@tanstack/react-table';

export const columns: ColumnDef<Member>[] = [
  selectionHeader as ColumnDef<Member>,
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
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Gender" />,
    accessorKey: 'gender',
    filterFn: 'equalsString',
    meta: { filterVariant: 'select', translationPrefix: 'enum.gender.' },
    cell: function CellComponent({ row }) {
      const { t } = useTranslations();
      return <DatatableBadgeCell className="w-24">{t(`enum.gender.${row.original.gender}`)}</DatatableBadgeCell>;
    },
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Civil status" />,
    accessorKey: 'civilStatus',
    filterFn: 'equalsString',
    meta: { filterVariant: 'select', translationPrefix: 'enum.civil_status.' },
    cell: function CellComponent({ row }) {
      const { t } = useTranslations();
      return <DatatableBadgeCell className="w-24">{t(`enum.civil_status.${row.original.civilStatus}`)}</DatatableBadgeCell>;
    },
  },
];
