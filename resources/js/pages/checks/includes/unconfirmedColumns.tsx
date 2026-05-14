import CheckController from '@/actions/App/Http/Controllers/CheckController';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/datatable-actions-dropdown';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { selectionHeader } from '@/components/datatable/columns';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { Badge } from '@/components/ui/badge';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { useCurrency } from '@/hooks/use-currency';
import useConfirmationStore from '@/stores/confirmation-store';
import type { Check } from '@/types/models/check';
import { Link, router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, Trash2Icon } from 'lucide-react';
import { useTranslation } from 'react-i18next';

export const unconfirmedColumns: ColumnDef<Check>[] = [
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
      const { t: tEnum } = useTranslation('enum');
      const { type } = row.original;
      return (
        <DatatableCell justify="center">
          <Badge>{tEnum(($) => $.checkType[type])}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DatatableHeader justify="end" column={column} title="Expense type" />,
    accessorKey: 'expenseType',
    cell: function CellComponent({ row }) {
      const { expenseType } = row.original;
      return (
        <DatatableCell justify="end">
          <Badge>{expenseType.name}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader column={column} title="Amount" />,
    accessorKey: 'transaction.amountFloat',
    cell: function CellComponent({ row }) {
      const { formatCurrency, toPositive } = useCurrency();

      const { transaction } = row.original;
      return <DatatableCell justify="center">{formatCurrency(toPositive(transaction.amountFloat))}</DatatableCell>;
    },
  },
  {
    id: 'actions',
    enableHiding: false,
    enableSorting: false,
    size: 0,
    cell: function CellComponent({ row }) {
      const { t: tPages } = useTranslation('pages');
      const { openConfirmation } = useConfirmationStore();
      const check = row.original;

      return (
        <DatatableActionsDropdown>
          {/* {userCan(UserPermission.UPDATE_CATEGORIES) && ( */}
          <DropdownMenuItem asChild>
            <Link href={CheckController.edit(check.id)}>
              <Edit2Icon className="size-3" />
              <span>{tPages(($) => $.checks.includes.unconfirmedColumns.edit)}</span>
            </Link>
          </DropdownMenuItem>
          {/* )} */}

          {/* {userCan(UserPermission.DELETE_CATEGORIES) && ( */}
          <DropdownMenuItem
            variant="destructive"
            onClick={() => {
              openConfirmation({
                title: tPages(($) => $.checks.includes.unconfirmedColumns.areYouSureYouWantToDeleteThisModel, {
                  model: tPages(($) => $.checks.includes.unconfirmedColumns.check),
                }),
                description: tPages(($) => $.checks.includes.unconfirmedColumns.thisActionCannotBeUndone),
                actionLabel: tPages(($) => $.checks.includes.unconfirmedColumns.delete),
                actionVariant: 'destructive',
                cancelLabel: tPages(($) => $.checks.includes.unconfirmedColumns.cancel),
                onAction: () => {
                  router.visit(CheckController.destroy(check.id), {
                    preserveState: true,
                    preserveScroll: true,
                  });
                },
              });
            }}
          >
            <Trash2Icon className="size-3" />
            <span>{tPages(($) => $.checks.includes.unconfirmedColumns.delete)}</span>
          </DropdownMenuItem>
          {/* )} */}
        </DatatableActionsDropdown>
      );
    },
  },
];
