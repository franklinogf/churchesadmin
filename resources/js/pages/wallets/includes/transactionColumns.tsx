import { DatatableCellBoolean } from '@/components/custom-ui/datatable/datatable-cell-boolean';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { useTranslations } from '@/hooks/use-translations';
import type { Transaction } from '@/types/models/transaction';
import { type ColumnDef } from '@tanstack/react-table';

export const transactionColumns: ColumnDef<Transaction>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Type" />,
    accessorKey: 'type',
    cell: function CellColumn({ row }) {
      const { t } = useTranslations();
      return (
        <DatatableCell justify="center">
          <Badge>{t(`enum.transaction_type.${row.original.type}`)}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="From" />,
    accessorKey: 'meta',
    cell: function CellColumn({ row }) {
      const { t } = useTranslations();
      if (!row.original.meta) return null;
      return (
        <DatatableCell justify="center">
          <Badge variant="outline">{t(`enum.transaction_meta_type.${row.original.meta.type}`)}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    enableHiding: false,
    accessorKey: 'amountFloat',
    header: ({ column }) => <DataTableColumnHeader column={column} title="Amount" />,
    cell: ({ row }) => <DatatableCell justify="end">${row.original.amountFloat}</DatatableCell>,
  },
  {
    accessorKey: 'confirmed',
    header: ({ column }) => <DataTableColumnHeader column={column} title="Confirmed" />,
    cell: ({ row }) => <DatatableCellBoolean trueCondition={row.original.confirmed} />,
  },
  {
    accessorKey: 'createdAt',
    header: ({ column }) => <DataTableColumnHeader column={column} title="Date" />,
    cell: ({ row }) => <DatatableCell justify="center">{row.original.createdAt}</DatatableCell>,
  },
];
