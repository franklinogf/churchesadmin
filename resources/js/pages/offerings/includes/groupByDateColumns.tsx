import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/data-table-actions-dropdown';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { useCurrency } from '@/hooks/use-currency';
import { useTranslations } from '@/hooks/use-translations';
import type { OfferingGroupedByDate } from '@/types/models/offering';
import { Link } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { FilesIcon } from 'lucide-react';

export const groupByDateColumns: ColumnDef<OfferingGroupedByDate>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Date" />,
    accessorKey: 'date',
  },
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Cash" />,
    accessorKey: 'cash',
    cell: function CellComponent({ row }) {
      const { formatCurrency } = useCurrency();
      return <DatatableCell justify="end">{formatCurrency(row.original.cash)}</DatatableCell>;
    },
  },
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Check" />,
    accessorKey: 'check',
    cell: function CellComponent({ row }) {
      const { formatCurrency } = useCurrency();
      return <DatatableCell justify="end">{formatCurrency(row.original.check)}</DatatableCell>;
    },
  },
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Total" />,
    accessorKey: 'total',
    cell: function CellComponent({ row }) {
      const { formatCurrency } = useCurrency();
      return <DatatableCell justify="end">{formatCurrency(row.original.total)}</DatatableCell>;
    },
  },
  {
    id: 'actions',
    enableHiding: false,
    enableSorting: false,
    size: 0,
    cell: function CellComponent({ row }) {
      const { t } = useTranslations();

      return (
        <DatatableActionsDropdown>
          <DropdownMenuItem asChild>
            <Link href={route('offerings.index', { date: row.original.date })}>
              <FilesIcon className="size-3" />
              <span>{t('Offerings')}</span>
            </Link>
          </DropdownMenuItem>
        </DatatableActionsDropdown>
      );
    },
  },
];
