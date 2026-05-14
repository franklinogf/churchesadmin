import { DatatableCellBoolean } from '@/components/custom-ui/datatable/datatable-cell-boolean';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { Badge } from '@/components/ui/badge';
import type { Transaction } from '@/types/models/transaction';
import { type ColumnDef } from '@tanstack/react-table';
import { useTranslation } from 'react-i18next';

export const transactionColumns: ColumnDef<Transaction>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader column={column} title="Type" />,
    accessorKey: 'type',
    cell: function CellColumn({ row }) {
      const { t: tEnum } = useTranslation('enum');
      return (
        <DatatableCell justify="center">
          <Badge>{tEnum(($) => $.transactionType[row.original.type as keyof typeof $.transactionType])}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader column={column} title="From" />,
    accessorKey: 'meta',
    cell: function CellColumn({ row }) {
      const { t: tEnum } = useTranslation('enum');
      const meta = row.original.meta;
      if (!meta) return null;
      return (
        <DatatableCell justify="center">
          <Badge variant="outline">{tEnum(($) => $.transactionMetaType[meta.type as keyof typeof $.transactionMetaType])}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    enableHiding: false,
    accessorKey: 'amountFloat',
    header: ({ column }) => <DatatableHeader column={column} title="Amount" />,
    cell: ({ row }) => <DatatableCell justify="end">${row.original.amountFloat}</DatatableCell>,
  },
  {
    accessorKey: 'confirmed',
    header: ({ column }) => <DatatableHeader column={column} title="Confirmed" />,
    cell: ({ row }) => <DatatableCellBoolean trueCondition={row.original.confirmed} />,
  },
  {
    accessorKey: 'createdAt',
    header: ({ column }) => <DatatableHeader column={column} title="Date" />,
    cell: ({ row }) => <DatatableCell justify="center">{row.original.createdAt}</DatatableCell>,
  },
];
