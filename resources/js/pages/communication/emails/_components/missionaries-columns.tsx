import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { selectionHeader } from '@/components/datatable/columns';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { Badge } from '@/components/ui/badge';
import type { Missionary } from '@/types/models/missionary';
import type { ColumnDef } from '@tanstack/react-table';
import { useTranslation } from 'react-i18next';

export const columns: ColumnDef<Missionary>[] = [
  selectionHeader as ColumnDef<Missionary>,
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
      return (
        <DatatableCell justify="center">
          <Badge className="w-24">{tEnum(($) => $.gender[row.original.gender])}</Badge>
        </DatatableCell>
      );
    },
  },
];
