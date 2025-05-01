import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useCurrency } from '@/hooks/use-currency';
import useConfirmationStore from '@/stores/confirmationStore';
import type { Expense } from '@/types/models/expense';
import { Link, router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { Edit2Icon, FileIcon, MoreHorizontalIcon, Trash2Icon } from 'lucide-react';

export const columns: ColumnDef<Expense>[] = [
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
    header: ({ column }) => <DataTableColumnHeader column={column} title="Expense Type" />,
    accessorKey: 'expenseType',
    cell: ({ row }) => (
      <DatatableCell justify="center">
        <Badge>{row.original.expenseType.name}</Badge>
      </DatatableCell>
    ),
  },
  {
    enableHiding: true,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Member" />,
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
    header: ({ column }) => <DataTableColumnHeader column={column} title="Amount" />,
    cell: function CellComponent({ row }) {
      const { formatCurrency } = useCurrency();
      return <DatatableCell justify="end">{formatCurrency(row.original.transaction.amountFloat)}</DatatableCell>;
    },
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
      const { openConfirmation } = useConfirmationStore();
      //   const { can: userCan } = useUser();
      const expense = row.original;

      return (
        <DropdownMenu>
          <DropdownMenuTrigger asChild>
            <Button variant="ghost" size="sm">
              <MoreHorizontalIcon />
              <span className="sr-only">{t('Actions')}</span>
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent>
            <DropdownMenuItem asChild>
              <Link href={route('expenses.show', expense.id)}>
                <FileIcon className="size-3" />
                <span>{t('View')}</span>
              </Link>
            </DropdownMenuItem>
            {/* {userCan(UserPermission.UPDATE_SKILLS) && ( */}

            <DropdownMenuItem asChild>
              <Link href={route('expenses.edit', expense.id)}>
                <Edit2Icon className="size-3" />
                <span>{t('Edit')}</span>
              </Link>
            </DropdownMenuItem>

            {/* )} */}

            <DropdownMenuItem
              variant="destructive"
              onClick={() => {
                openConfirmation({
                  title: t('Are you sure you want to delete this expense?'),
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
          </DropdownMenuContent>
        </DropdownMenu>
      );
    },
  },
];
