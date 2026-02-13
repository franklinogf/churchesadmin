import ExpenseTypeController from '@/actions/App/Http/Controllers/ExpenseTypeController';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/data-table-actions-dropdown';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { ExpenseTypeForm } from '@/components/forms/expense-type-form';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { useCurrency } from '@/hooks/use-currency';
import { useTranslations } from '@/hooks/use-translations';
import useConfirmationStore from '@/stores/confirmationStore';
import type { ExpenseType } from '@/types/models/expense-type';
import { router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, Trash2Icon } from 'lucide-react';
import { useState } from 'react';

export const columns: ColumnDef<ExpenseType>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Name" />,
    accessorKey: 'name',
  },
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Default amount" />,
    accessorKey: 'defaultAmount',
    cell: function CellComponent({ row }) {
      const { formatCurrency } = useCurrency();
      const defaultAmount = row.original.defaultAmount;
      if (defaultAmount === null) return null;
      return <DatatableCell justify="end">{formatCurrency(defaultAmount)}</DatatableCell>;
    },
  },
  {
    id: 'actions',
    enableHiding: false,
    enableSorting: false,
    size: 0,
    cell: function CellComponent({ row }) {
      const { t } = useTranslations();
      const { openConfirmation } = useConfirmationStore();
      const [isEditing, setIsEditing] = useState(false);
      const expenseType = row.original;

      return (
        <>
          <ExpenseTypeForm open={isEditing} setOpen={setIsEditing} expenseType={expenseType} />
          <DatatableActionsDropdown>
            <DropdownMenuItem onSelect={() => setIsEditing(true)}>
              <Edit2Icon className="size-3" />
              <span>{t('Edit')}</span>
            </DropdownMenuItem>

            <DropdownMenuItem
              variant="destructive"
              onClick={() => {
                openConfirmation({
                  title: t('Are you sure you want to delete this :model?', { model: t('Expense type') }),
                  description: t('This action cannot be undone.'),
                  actionLabel: t('Delete'),
                  actionVariant: 'destructive',
                  cancelLabel: t('Cancel'),
                  onAction: () => {
                    router.visit(ExpenseTypeController.destroy(expenseType.id), {
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
        </>
      );
    },
  },
];
