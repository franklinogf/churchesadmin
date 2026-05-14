import ExpenseController from '@/actions/App/Http/Controllers/ExpenseController';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/datatable-actions-dropdown';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { Badge } from '@/components/ui/badge';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { useCurrency } from '@/hooks/use-currency';
import useConfirmationStore from '@/stores/confirmation-store';
import type { Expense } from '@/types/models/expense';
import { Link, router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, FileIcon, Trash2Icon } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import { ViewExpenseModal } from '../components/ViewExpenseModal';

export const columns: ColumnDef<Expense>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader justify="center" column={column} title="Wallet" />,
    accessorKey: 'transaction',
    cell: ({ row }) => (
      <DatatableCell justify="center">
        <Badge variant="secondary">{row.original.transaction.wallet?.name}</Badge>
      </DatatableCell>
    ),
  },
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader justify="center" column={column} title="Expense type" />,
    accessorKey: 'expenseType',
    cell: ({ row }) => (
      <DatatableCell justify="center">
        <Badge>{row.original.expenseType.name}</Badge>
      </DatatableCell>
    ),
  },
  {
    enableHiding: true,
    header: ({ column }) => <DatatableHeader justify="center" column={column} title="Member" />,
    accessorKey: 'member',
    cell: ({ row }) => {
      const { member } = row.original;
      if (member === null) return null;
      return `${member.name} ${member.lastName}`;
    },
  },
  {
    enableHiding: false,
    accessorKey: 'amount',
    header: ({ column }) => <DatatableHeader justify="end" column={column} title="Amount" />,
    cell: function CellComponent({ row }) {
      const { formatCurrency, toPositive } = useCurrency();
      return <DatatableCell justify="end">{formatCurrency(toPositive(row.original.transaction.amountFloat))}</DatatableCell>;
    },
  },
  {
    accessorKey: 'date',
    header: ({ column }) => <DatatableHeader justify="center" column={column} title="Date" />,
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
      //   const { can: userCan } = useUser();
      const expense = row.original;

      return (
        <DatatableActionsDropdown>
          <ViewExpenseModal expense={expense}>
            <DropdownMenuItem onSelect={(e) => e.preventDefault()}>
              <FileIcon className="size-3" />
              <span>{tPages(($) => $.expenses.includes.columns.view)}</span>
            </DropdownMenuItem>
          </ViewExpenseModal>
          {/* {userCan(UserPermission.UPDATE_SKILLS) && ( */}

          <DropdownMenuItem asChild>
            <Link href={ExpenseController.edit(expense.id)}>
              <Edit2Icon className="size-3" />
              <span>{tPages(($) => $.expenses.includes.columns.edit)}</span>
            </Link>
          </DropdownMenuItem>

          {/* )} */}

          <DropdownMenuItem
            variant="destructive"
            onClick={() => {
              openConfirmation({
                title: tPages(($) => $.expenses.includes.columns.areYouSureYouWantToDeleteThisModel, {
                  model: tPages(($) => $.expenses.includes.columns.expense),
                }),
                description: tPages(($) => $.expenses.includes.columns.thisActionCannotBeUndone),
                actionLabel: tPages(($) => $.expenses.includes.columns.delete),
                actionVariant: 'destructive',
                cancelLabel: tPages(($) => $.expenses.includes.columns.cancel),
                onAction: () => {
                  router.visit(ExpenseController.destroy(expense.id), {
                    preserveScroll: true,
                  });
                },
              });
            }}
          >
            <Trash2Icon className="size-3" />
            <span>{tPages(($) => $.expenses.includes.columns.delete)}</span>
          </DropdownMenuItem>
        </DatatableActionsDropdown>
      );
    },
  },
];
