import ExpenseTypeController from '@/actions/App/Http/Controllers/ExpenseTypeController';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/datatable-actions-dropdown';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { ExpenseTypeForm } from '@/components/forms/expense-type-form';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { useCurrency } from '@/hooks/use-currency';
import useConfirmationStore from '@/stores/confirmation-store';
import type { ExpenseType } from '@/types/models/expense-type';
import { router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, Trash2Icon } from 'lucide-react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';

export const columns: ColumnDef<ExpenseType>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader column={column} title="Name" />,
    accessorKey: 'name',
  },
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader column={column} title="Default amount" />,
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
      const { t: tPages } = useTranslation('pages');
      const { openConfirmation } = useConfirmationStore();
      const [isEditing, setIsEditing] = useState(false);
      const expenseType = row.original;

      return (
        <>
          <ExpenseTypeForm open={isEditing} setOpen={setIsEditing} expenseType={expenseType} />
          <DatatableActionsDropdown>
            <DropdownMenuItem onSelect={() => setIsEditing(true)}>
              <Edit2Icon className="size-3" />
              <span>{tPages(($) => $.codes.expenseTypes.includes.columns.edit)}</span>
            </DropdownMenuItem>

            <DropdownMenuItem
              variant="destructive"
              onClick={() => {
                openConfirmation({
                  title: tPages(($) => $.codes.expenseTypes.includes.columns.areYouSureYouWantToDeleteThisModel, {
                    model: tPages(($) => $.codes.expenseTypes.includes.columns.expenseType),
                  }),
                  description: tPages(($) => $.codes.expenseTypes.includes.columns.thisActionCannotBeUndone),
                  actionLabel: tPages(($) => $.codes.expenseTypes.includes.columns.delete),
                  actionVariant: 'destructive',
                  cancelLabel: tPages(($) => $.codes.expenseTypes.includes.columns.cancel),
                  onAction: () => {
                    router.visit(ExpenseTypeController.destroy(expenseType.id), {
                      preserveScroll: true,
                    });
                  },
                });
              }}
            >
              <Trash2Icon className="size-3" />
              <span>{tPages(($) => $.codes.expenseTypes.includes.columns.delete)}</span>
            </DropdownMenuItem>
          </DatatableActionsDropdown>
        </>
      );
    },
  },
];
