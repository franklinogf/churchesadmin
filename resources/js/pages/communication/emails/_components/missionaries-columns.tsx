import { selectionHeader } from '@/components/custom-ui/datatable/columns';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { useTranslations } from '@/hooks/use-translations';
import type { Missionary } from '@/types/models/missionary';
import type { ColumnDef } from '@tanstack/react-table';

export const columns: ColumnDef<Missionary>[] = [
  selectionHeader as ColumnDef<Missionary>,
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
      return (
        <DatatableCell justify="center">
          <Badge className="w-24">{t(`enum.gender.${row.original.gender}`)}</Badge>
        </DatatableCell>
      );
    },
  },
];
