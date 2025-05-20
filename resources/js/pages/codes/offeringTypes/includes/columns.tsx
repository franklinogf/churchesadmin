import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/data-table-actions-dropdown';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { OfferingTypeForm } from '@/components/forms/offering-type-form';
import { DropdownMenuContent, DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { useTranslations } from '@/hooks/use-translations';
import useConfirmationStore from '@/stores/confirmationStore';
import { type OfferingType } from '@/types/models/offering-type';
import { router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, Trash2Icon } from 'lucide-react';
import { useState } from 'react';

export const columns: ColumnDef<OfferingType>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} center={false} title="Name" />,
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
      const offeringType = row.original;

      return (
        <>
          <OfferingTypeForm offeringType={offeringType} open={isEditing} setOpen={setIsEditing} />
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
                    title: t('Are you sure you want to delete this :model?', { model: t('Offering type') }),
                    description: t('This action cannot be undone.'),
                    actionLabel: t('Delete'),
                    actionVariant: 'destructive',
                    cancelLabel: t('Cancel'),
                    onAction: () => {
                      router.delete(route('codes.offeringTypes.destroy', offeringType.id), {
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
