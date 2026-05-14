import DeactivationCodeController from '@/actions/App/Http/Controllers/DeactivationCodeController';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/datatable-actions-dropdown';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { DeactivationCodeForm } from '@/components/forms/deactivation-code-form';
import { DropdownMenuContent, DropdownMenuItem } from '@/components/ui/dropdown-menu';
import useConfirmationStore from '@/stores/confirmation-store';
import { type DeactivationCode } from '@/types/models/deactivation-code';
import { router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, Trash2Icon } from 'lucide-react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';

export const columns: ColumnDef<DeactivationCode>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader column={column} title="Name" />,
    accessorKey: 'name',
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
      const deactivationCode = row.original;

      return (
        <>
          <DeactivationCodeForm deactivationCode={deactivationCode} open={isEditing} setOpen={setIsEditing} />
          <DatatableActionsDropdown>
            <DropdownMenuContent>
              <DropdownMenuItem onSelect={() => setIsEditing(true)}>
                <Edit2Icon className="size-3" />
                <span>{tPages(($) => $.codes.deactivationCodes.includes.columns.edit)}</span>
              </DropdownMenuItem>

              <DropdownMenuItem
                variant="destructive"
                onClick={() => {
                  openConfirmation({
                    title: tPages(($) => $.codes.deactivationCodes.includes.columns.areYouSureYouWantToDeleteThisModel, {
                      model: tPages(($) => $.codes.deactivationCodes.includes.columns.deactivationCode),
                    }),
                    description: tPages(($) => $.codes.deactivationCodes.includes.columns.thisActionCannotBeUndone),
                    actionLabel: tPages(($) => $.codes.deactivationCodes.includes.columns.delete),
                    actionVariant: 'destructive',
                    cancelLabel: tPages(($) => $.codes.deactivationCodes.includes.columns.cancel),
                    onAction: () => {
                      router.visit(DeactivationCodeController.destroy(deactivationCode.id), {
                        preserveScroll: true,
                      });
                    },
                  });
                }}
              >
                <Trash2Icon className="size-3" />
                <span>{tPages(($) => $.codes.deactivationCodes.includes.columns.delete)}</span>
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DatatableActionsDropdown>
        </>
      );
    },
  },
];
