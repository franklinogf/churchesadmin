import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { selectionHeader } from '@/components/datatable/columns';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { Badge } from '@/components/ui/badge';
import { useCurrency } from '@/hooks/use-currency';
import type { Check } from '@/types/models/check';
import { type ColumnDef } from '@tanstack/react-table';
import { useTranslation } from 'react-i18next';

export const confirmedColumns: ColumnDef<Check>[] = [
  selectionHeader as ColumnDef<Check>,
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader column={column} title="Member" />,
    accessorKey: 'member',
    cell: ({ row }) => {
      const { member } = row.original;
      return `${member.name} ${member.lastName}`;
    },
  },
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader justify="center" column={column} title="Date" />,
    accessorKey: 'date',
    cell: ({ row }) => {
      const { date } = row.original;
      return <DatatableCell justify="center">{date}</DatatableCell>;
    },
  },
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader justify="center" column={column} title="Number" />,
    accessorKey: 'checkNumber',
    cell: function CellComponent({ row }) {
      const { checkNumber } = row.original;
      if (checkNumber === null) return null;
      return (
        <DatatableCell justify="center">
          <Badge className="bg-brand">{checkNumber}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DatatableHeader justify="center" column={column} title="Type" />,
    accessorKey: 'type',
    cell: function CellComponent({ row }) {
      const { t } = useTranslation('enum');
      const { type } = row.original;
      return (
        <DatatableCell justify="center">
          <Badge>{t(($) => $.checkType[type])}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DatatableHeader justify="center" column={column} title="Expense type" />,
    accessorKey: 'expenseType',
    cell: function CellComponent({ row }) {
      const { expenseType } = row.original;
      return (
        <DatatableCell justify="center">
          <Badge>{expenseType.name}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader justify="end" column={column} title="Amount" />,
    accessorKey: 'transaction.amountFloat',
    cell: function CellComponent({ row }) {
      const { formatCurrency, toPositive } = useCurrency();

      const { transaction } = row.original;
      return <DatatableCell justify="end">{formatCurrency(toPositive(transaction.amountFloat))}</DatatableCell>;
    },
  },
];
