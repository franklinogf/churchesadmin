import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/data-table-actions-dropdown';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { useCurrency } from '@/hooks/use-currency';
import { useTranslations } from '@/hooks/use-translations';
import useConfirmationStore from '@/stores/confirmationStore';
import type { Expense } from '@/types/models/expense';
import { Link, router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, FileIcon, Trash2Icon } from 'lucide-react';
import { ViewExpenseModal } from '../components/ViewExpenseModal';

export const columns: ColumnDef<Expense>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader justify="center" column={column} title="Wallet" />,
    accessorKey: 'transaction',
    cell: ({ row }) => (
      <DatatableCell justify="center">
        <Badge variant="secondary">{row.original.transaction.wallet?.name}</Badge>
      </DatatableCell>
    ),
  },
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader justify="center" column={column} title="Expense type" />,
    accessorKey: 'expenseType',
    cell: ({ row }) => (
      <DatatableCell justify="center">
        <Badge>{row.original.expenseType.name}</Badge>
      </DatatableCell>
    ),
  },
  {
    enableHiding: true,
    header: ({ column }) => <DataTableColumnHeader justify="center" column={column} title="Member" />,
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
    header: ({ column }) => <DataTableColumnHeader justify="end" column={column} title="Amount" />,
    cell: function CellComponent({ row }) {
      const { formatCurrency, toPositive } = useCurrency();
      return <DatatableCell justify="end">{formatCurrency(toPositive(row.original.transaction.amountFloat))}</DatatableCell>;
    },
  },
  {
    accessorKey: 'date',
    header: ({ column }) => <DataTableColumnHeader justify="center" column={column} title="Date" />,
    cell: ({ row }) => <DatatableCell justify="center">{row.original.date}</DatatableCell>,
  },
  {
    id: 'actions',
    enableHiding: false,
    enableSorting: false,
    size: 0,
    cell: function CellComponent({ row }) {
      const { t } = useTranslations();
      const { openConfirmation } = useConfirmationStore();
      //   const { can: userCan } = useUser();
      const expense = row.original;

      return (
        <DatatableActionsDropdown>
          <ViewExpenseModal expense={expense}>
            <DropdownMenuItem onSelect={(e) => e.preventDefault()}>
              <FileIcon className="size-3" />
              <span>{t('View')}</span>
            </DropdownMenuItem>
          </ViewExpenseModal>
          {/* {userCan(UserPermission.UPDATE_SKILLS) && ( */}

          <DropdownMenuItem asChild>
            <Link href={route('expenses.edit', expense.id)}>
              <Edit2Icon className="size-3" />
              <span>{t('Edit')}</span>
            </Link>
          </DropdownMenuItem>

          {/* )} */}

          <DropdownMenuItem
            // variant="destructive"
            onClick={() => {
              openConfirmation({
                title: t('Are you sure you want to delete this :model?', { model: t('Expense') }),
                description: t('This action cannot be undone'),
                actionLabel: t('Delete'),
                actionVariant: 'destructive',
                cancelLabel: t('Cancel'),
                onAction: () => {
                  router.delete(route('expenses.destroy', expense.id), {
                    preserveScroll: true,
                  });
                },
              });
            }}
          >
            <Trash2Icon className="size-3" />
            <span>{t('Delete')}</span>
          </DropdownMenuItem>
        </DatatableActionsDropdown>
      );
    },
  },
];
