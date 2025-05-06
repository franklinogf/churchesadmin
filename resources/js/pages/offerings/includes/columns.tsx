import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/data-table-actions-dropdown';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { HoverCard, HoverCardContent, HoverCardTrigger } from '@/components/ui/hover-card';
import { offeringTypeIsMissionary } from '@/lib/utils';
import type { Offering } from '@/types/models/offering';
import { Link } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { Edit2Icon } from 'lucide-react';

export const columns: ColumnDef<Offering>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Donor" />,
    accessorKey: 'donor',
    cell: ({ row }) => {
      const { donor } = row.original;
      if (!donor) return null;
      return (
        <HoverCard>
          <HoverCardTrigger asChild>
            <Button variant="link" size="sm" className="px-0">
              {`${donor.name} ${donor.lastName}`}
            </Button>
          </HoverCardTrigger>
          <HoverCardContent>{donor.email}</HoverCardContent>
        </HoverCard>
      );
    },
  },
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Offering Type" />,
    accessorKey: 'offeringType',
    sortingFn: (rowA, rowB) => {
      const offeringTypeA = rowA.original.offeringType;
      const offeringTypeB = rowB.original.offeringType;
      if (!offeringTypeA && !offeringTypeB) return 0;
      if (!offeringTypeA) return 1;
      if (!offeringTypeB) return -1;
      return offeringTypeA.name.localeCompare(offeringTypeB.name);
    },
    cell: ({ row }) => (
      <DatatableCell justify="center">
        <Badge>
          {offeringTypeIsMissionary(row.original.offeringType)
            ? `${row.original.offeringType.name} ${row.original.offeringType.lastName}`
            : row.original.offeringType.name}
        </Badge>
      </DatatableCell>
    ),
  },
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Wallet" />,
    accessorKey: 'transaction',
    cell: ({ row }) => (
      <DatatableCell justify="center">
        <Badge variant="secondary">{row.original.transaction.wallet?.name}</Badge>
      </DatatableCell>
    ),
  },
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Payment method" />,
    accessorKey: 'paymentMethod',
    cell: function CellComponent({ row }) {
      const { t } = useLaravelReactI18n();
      return (
        <DatatableCell justify="center">
          <Badge>{t(`enum.payment_method.${row.original.paymentMethod}`)}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    enableHiding: false,
    accessorKey: 'amountFloat',
    header: ({ column }) => <DataTableColumnHeader column={column} title="Amount" />,
    cell: ({ row }) => <DatatableCell justify="end">${row.original.transaction.amountFloat}</DatatableCell>,
  },
  {
    accessorKey: 'date',
    header: ({ column }) => <DataTableColumnHeader column={column} title="Date" />,
    meta: 'Date',
    cell: ({ row }) => <DatatableCell justify="center">{row.original.date}</DatatableCell>,
  },
  {
    id: 'actions',
    enableHiding: false,
    enableSorting: false,
    size: 0,
    cell: function CellComponent({ row }) {
      const { t } = useLaravelReactI18n();

      return (
        <DatatableActionsDropdown>
          <DropdownMenuItem asChild>
            <Link href={route('offerings.edit', { offering: row.original.id })}>
              <Edit2Icon className="size-3" />
              <span>{t('Edit')}</span>
            </Link>
          </DropdownMenuItem>
          <DropdownMenuItem>
            <Link method="delete" href={route('offerings.destroy', { offering: row.original.id })}>
              <span>{t('Delete')}</span>
            </Link>
          </DropdownMenuItem>
        </DatatableActionsDropdown>
      );
    },
  },
];
