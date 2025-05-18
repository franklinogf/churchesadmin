import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useCurrency } from '@/hooks/use-currency';
import useConfirmationStore from '@/stores/confirmationStore';
import type { Check } from '@/types/models/check';
import { Link, router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { Edit2Icon, MoreHorizontalIcon, Trash2Icon } from 'lucide-react';

export const columns: ColumnDef<Check>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader center={false} column={column} title="Member" />,
    accessorKey: 'member',
    cell: ({ row }) => {
      const { member } = row.original;
      return `${member.name} ${member.lastName}`;
    },
  },
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Date" />,
    accessorKey: 'date',
    cell: ({ row }) => {
      const { date } = row.original;
      return <DatatableCell justify="center">{date}</DatatableCell>;
    },
  },
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Number" />,
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
    header: ({ column }) => <DataTableColumnHeader column={column} title="Type" />,
    accessorKey: 'type',
    cell: function CellComponent({ row }) {
      const { t } = useLaravelReactI18n();
      const { type } = row.original;
      return (
        <DatatableCell justify="center">
          <Badge>{t(`enum.check_type.${type}`)}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Expense type" />,
    accessorKey: 'expenseType',
    meta: 'Expense type',
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
    header: ({ column }) => <DataTableColumnHeader column={column} title="Amount" />,
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
      const { t } = useLaravelReactI18n();
      const { openConfirmation } = useConfirmationStore();
      const check = row.original;

      return (
        <DropdownMenu>
          <DropdownMenuTrigger asChild>
            <Button variant="ghost" size="sm">
              <MoreHorizontalIcon />
              <span className="sr-only">{t('Actions')}</span>
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent>
            {/* {userCan(UserPermission.UPDATE_CATEGORIES) && ( */}
            <DropdownMenuItem asChild>
              <Link href={route('checks.edit', check.id)}>
                <Edit2Icon className="size-3" />
                <span>{t('Edit')}</span>
              </Link>
            </DropdownMenuItem>
            {/* )} */}

            {/* {userCan(UserPermission.DELETE_CATEGORIES) && ( */}
            <DropdownMenuItem
              variant="destructive"
              onClick={() => {
                openConfirmation({
                  title: t('Are you sure you want to delete this :model', { model: t('Check') }),
                  description: t('This action cannot be undone.'),
                  actionLabel: t('Delete'),
                  actionVariant: 'destructive',
                  cancelLabel: t('Cancel'),
                  onAction: () => {
                    router.delete(route('checks.destroy', check.id), {
                      preserveState: true,
                      preserveScroll: true,
                    });
                  },
                });
              }}
            >
              <Trash2Icon className="size-3" />
              <span>{t('Delete')}</span>
            </DropdownMenuItem>
            {/* )} */}
          </DropdownMenuContent>
        </DropdownMenu>
      );
    },
  },
];
