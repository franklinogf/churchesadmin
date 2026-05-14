import OfferingController from '@/actions/App/Http/Controllers/OfferingController';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/datatable-actions-dropdown';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { HoverCard, HoverCardContent, HoverCardTrigger } from '@/components/ui/hover-card';
import { offeringTypeIsMissionary } from '@/lib/utils';
import useConfirmationStore from '@/stores/confirmation-store';
import type { Offering } from '@/types/models/offering';
import { Link, router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, Trash2Icon } from 'lucide-react';
import { useTranslation } from 'react-i18next';

export const columns: ColumnDef<Offering>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader column={column} title="Donor" />,
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
    header: ({ column }) => <DatatableHeader column={column} title="Offering type" />,
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
    header: ({ column }) => <DatatableHeader column={column} title="Wallet" />,
    accessorKey: 'transaction',
    cell: ({ row }) => (
      <DatatableCell justify="center">
        <Badge variant="secondary">{row.original.transaction.wallet?.name}</Badge>
      </DatatableCell>
    ),
  },
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader column={column} title="Payment method" />,
    accessorKey: 'paymentMethod',
    cell: function CellComponent({ row }) {
      const { t: tEnum } = useTranslation('enum');
      return (
        <DatatableCell justify="center">
          <Badge>{tEnum(($) => $.paymentMethod[row.original.paymentMethod])}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    enableHiding: false,
    accessorKey: 'amountFloat',
    header: ({ column }) => <DatatableHeader column={column} title="Amount" />,
    cell: ({ row }) => <DatatableCell justify="end">${row.original.transaction.amountFloat}</DatatableCell>,
  },
  {
    accessorKey: 'date',
    header: ({ column }) => <DatatableHeader column={column} title="Date" />,
    cell: ({ row }) => <DatatableCell justify="center">{row.original.date}</DatatableCell>,
  },
  {
    id: 'actions',
    enableHiding: false,
    enableSorting: false,
    size: 0,
    cell: function CellComponent({ row }) {
      const { t: tPages } = useTranslation('pages');
      const { openConfirmation } = useConfirmationStore();

      return (
        <DatatableActionsDropdown>
          <DropdownMenuItem asChild>
            <Link href={OfferingController.edit({ offering: row.original.id })}>
              <Edit2Icon className="size-3" />
              <span>{tPages(($) => $.offerings.includes.columns.edit)}</span>
            </Link>
          </DropdownMenuItem>
          <DropdownMenuItem
            variant="destructive"
            onClick={() => {
              openConfirmation({
                title: tPages(($) => $.offerings.includes.columns.areYouSureYouWantToDeleteThisModel, {
                  model: tPages(($) => $.offerings.includes.columns.offering),
                }),
                description: tPages(($) => $.offerings.includes.columns.youCanRestoreItAnyTime),
                actionLabel: tPages(($) => $.offerings.includes.columns.delete),
                actionVariant: 'destructive',
                cancelLabel: tPages(($) => $.offerings.includes.columns.cancel),
                onAction: () => {
                  router.visit(OfferingController.destroy(row.original.id));
                },
              });
            }}
          >
            <Trash2Icon className="size-3" />
            <span>{tPages(($) => $.offerings.includes.columns.delete)}</span>
          </DropdownMenuItem>
        </DatatableActionsDropdown>
      );
    },
  },
];
