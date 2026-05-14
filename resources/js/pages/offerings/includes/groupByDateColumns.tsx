import OfferingController from '@/actions/App/Http/Controllers/OfferingController';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/datatable-actions-dropdown';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { useCurrency } from '@/hooks/use-currency';
import type { OfferingGroupedByDate } from '@/types/models/offering';
import { Link } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { FilesIcon } from 'lucide-react';
import { useTranslation } from 'react-i18next';

export const groupByDateColumns: ColumnDef<OfferingGroupedByDate>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader column={column} title="Date" />,
    accessorKey: 'date',
  },
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader justify="end" column={column} title="Cash" />,
    accessorKey: 'cash',
    cell: function CellComponent({ row }) {
      const { formatCurrency } = useCurrency();
      return <DatatableCell justify="end">{formatCurrency(row.original.cash)}</DatatableCell>;
    },
  },
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader justify="end" column={column} title="Check" />,
    accessorKey: 'check',
    cell: function CellComponent({ row }) {
      const { formatCurrency } = useCurrency();
      return <DatatableCell justify="end">{formatCurrency(row.original.check)}</DatatableCell>;
    },
  },
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader justify="end" column={column} title="Total" />,
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
      const { t: tPages } = useTranslation('pages');
      return (
        <DatatableActionsDropdown>
          <DropdownMenuItem asChild>
            <Link href={OfferingController.index({ query: { date: row.original.date } })}>
              <FilesIcon className="size-3" />
              <span>{tPages(($) => $.offerings.includes.groupByDateColumns.offerings)}</span>
            </Link>
          </DropdownMenuItem>
        </DatatableActionsDropdown>
      );
    },
  },
];
