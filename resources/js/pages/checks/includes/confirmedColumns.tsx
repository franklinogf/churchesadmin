import { selectionHeader } from '@/components/custom-ui/datatable/columns';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { useCurrency } from '@/hooks/use-currency';
import { useTranslations } from '@/hooks/use-translations';
import type { Check } from '@/types/models/check';
import { type ColumnDef } from '@tanstack/react-table';

export const confirmedColumns: ColumnDef<Check>[] = [
  selectionHeader as ColumnDef<Check>,
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Member" />,
    accessorKey: 'member',
    cell: ({ row }) => {
      const { member } = row.original;
      return `${member.name} ${member.lastName}`;
    },
  },
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader justify="center" column={column} title="Date" />,
    accessorKey: 'date',
    cell: ({ row }) => {
      const { date } = row.original;
      return <DatatableCell justify="center">{date}</DatatableCell>;
    },
  },
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader justify="center" column={column} title="Number" />,
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
    header: ({ column }) => <DataTableColumnHeader justify="center" column={column} title="Type" />,
    accessorKey: 'type',
    cell: function CellComponent({ row }) {
      const { t } = useTranslations();
      const { type } = row.original;
      return (
        <DatatableCell justify="center">
          <Badge>{t(`enum.check_type.${type}`)}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DataTableColumnHeader justify="center" column={column} title="Expense type" />,
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
    header: ({ column }) => <DataTableColumnHeader justify="end" column={column} title="Amount" />,
    accessorKey: 'transaction.amountFloat',
    cell: function CellComponent({ row }) {
      const { formatCurrency, toPositive } = useCurrency();

      const { transaction } = row.original;
      return <DatatableCell justify="end">{formatCurrency(toPositive(transaction.amountFloat))}</DatatableCell>;
    },
  },
];
