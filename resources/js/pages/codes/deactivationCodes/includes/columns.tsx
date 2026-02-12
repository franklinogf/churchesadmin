import DeactivationCodeController from '@/actions/App/Http/Controllers/DeactivationCodeController';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/data-table-actions-dropdown';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { DeactivationCodeForm } from '@/components/forms/deactivation-code-form';
import { DropdownMenuContent, DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { useTranslations } from '@/hooks/use-translations';
import useConfirmationStore from '@/stores/confirmationStore';
import { type DeactivationCode } from '@/types/models/deactivation-code';
import { router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, Trash2Icon } from 'lucide-react';
import { useState } from 'react';

export const columns: ColumnDef<DeactivationCode>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Name" />,
    accessorKey: 'name',
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
      const deactivationCode = row.original;

      return (
        <>
          <DeactivationCodeForm deactivationCode={deactivationCode} open={isEditing} setOpen={setIsEditing} />
          <DatatableActionsDropdown>
            <DropdownMenuContent>
              <DropdownMenuItem onSelect={() => setIsEditing(true)}>
                <Edit2Icon className="size-3" />
                <span>{t('Edit')}</span>
              </DropdownMenuItem>

              <DropdownMenuItem
                variant="destructive"
                onClick={() => {
                  openConfirmation({
                    title: t('Are you sure you want to delete this :model?', { model: t('Deactivation code') }),
                    description: t('This action cannot be undone.'),
                    actionLabel: t('Delete'),
                    actionVariant: 'destructive',
                    cancelLabel: t('Cancel'),
                    onAction: () => {
                      router.visit(DeactivationCodeController.destroy(deactivationCode.id), {
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
          </DatatableActionsDropdown>
        </>
      );
    },
  },
];
