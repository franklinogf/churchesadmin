import OfferingTypeController from '@/actions/App/Http/Controllers/OfferingTypeController';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/datatable-actions-dropdown';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { OfferingTypeForm } from '@/components/forms/offering-type-form';
import { DropdownMenuContent, DropdownMenuItem } from '@/components/ui/dropdown-menu';
import useConfirmationStore from '@/stores/confirmation-store';
import { type OfferingType } from '@/types/models/offering-type';
import { router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, Trash2Icon } from 'lucide-react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';

export const columns: ColumnDef<OfferingType>[] = [
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
      const offeringType = row.original;

      return (
        <>
          <OfferingTypeForm offeringType={offeringType} open={isEditing} setOpen={setIsEditing} />
          <DatatableActionsDropdown>
            <DropdownMenuContent>
              <DropdownMenuItem onSelect={() => setIsEditing(true)}>
                <Edit2Icon className="size-3" />
                <span>{tPages(($) => $.codes.offeringTypes.includes.columns.edit)}</span>
              </DropdownMenuItem>

              <DropdownMenuItem
                variant="destructive"
                onClick={() => {
                  openConfirmation({
                    title: tPages(($) => $.codes.offeringTypes.includes.columns.areYouSureYouWantToDeleteThisModel, {
                      model: tPages(($) => $.codes.offeringTypes.includes.columns.offeringType),
                    }),
                    description: tPages(($) => $.codes.offeringTypes.includes.columns.thisActionCannotBeUndone),
                    actionLabel: tPages(($) => $.codes.offeringTypes.includes.columns.delete),
                    actionVariant: 'destructive',
                    cancelLabel: tPages(($) => $.codes.offeringTypes.includes.columns.cancel),
                    onAction: () => {
                      router.visit(OfferingTypeController.destroy(offeringType.id), {
                        preserveScroll: true,
                      });
                    },
                  });
                }}
              >
                <Trash2Icon className="size-3" />
                <span>{tPages(($) => $.codes.offeringTypes.includes.columns.delete)}</span>
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DatatableActionsDropdown>
        </>
      );
    },
  },
];
