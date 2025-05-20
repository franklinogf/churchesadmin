import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/data-table-actions-dropdown';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { CategoryForm } from '@/components/forms/category-form';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { UserPermission } from '@/enums/user';
import { useTranslations } from '@/hooks/use-translations';
import { useUser } from '@/hooks/use-user';
import useConfirmationStore from '@/stores/confirmationStore';
import { type Tag } from '@/types/models/tag';
import { router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, Trash2Icon } from 'lucide-react';
import { useState } from 'react';

export const columns: ColumnDef<Tag>[] = [
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
      const { can: userCan } = useUser();
      const [isEditing, setIsEditing] = useState(false);
      const category = row.original;
      if (category.isRegular && !userCan(UserPermission.REGULAR_TAGS_UPDATE) && !userCan(UserPermission.REGULAR_TAGS_DELETE)) {
        return null;
      }

      if (!userCan(UserPermission.CATEGORIES_UPDATE) && !userCan(UserPermission.CATEGORIES_DELETE)) {
        return null;
      }

      return (
        <>
          <CategoryForm category={category} open={isEditing} setOpen={setIsEditing} />
          <DatatableActionsDropdown>
            {userCan(UserPermission.CATEGORIES_UPDATE) && (
              <DropdownMenuItem onSelect={() => setIsEditing(true)}>
                <Edit2Icon className="size-3" />
                <span>{t('Edit')}</span>
              </DropdownMenuItem>
            )}

            {userCan(UserPermission.CATEGORIES_DELETE) && (
              <DropdownMenuItem
                variant="destructive"
                onClick={() => {
                  openConfirmation({
                    title: t('Are you sure you want to delete this :model?', { model: t('Category') }),
                    description: (category.isRegular ? t('This is marked as regular.') + '\n' : '') + t('This action cannot be undone.'),
                    actionLabel: t('Delete'),
                    actionVariant: 'destructive',
                    cancelLabel: t('Cancel'),
                    onAction: () => {
                      router.delete(route('categories.destroy', category.id), {
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
            )}
          </DatatableActionsDropdown>
        </>
      );
    },
  },
];
