import CategoryController from '@/actions/App/Http/Controllers/CategoryController';
import { DatatableCellActions } from '@/components/datatable/datatable-cell-actions';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { CategoryForm } from '@/components/forms/category-form';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import useConfirmationStore from '@/stores/confirmation-store';
import { type Tag } from '@/types/models/tag';
import { router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, Trash2Icon } from 'lucide-react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';

export const columns: ColumnDef<Tag>[] = [
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
      const { can: userCan } = useUser();
      const [isEditing, setIsEditing] = useState(false);
      const category = row.original;
      if (category.isRegular && !userCan(TenantPermission.REGULAR_TAGS_UPDATE) && !userCan(TenantPermission.REGULAR_TAGS_DELETE)) {
        return null;
      }

      if (!userCan(TenantPermission.CATEGORIES_UPDATE) && !userCan(TenantPermission.CATEGORIES_DELETE)) {
        return null;
      }

      return (
        <>
          <CategoryForm category={category} open={isEditing} setOpen={setIsEditing} />
          <DatatableCellActions>
            {userCan(TenantPermission.CATEGORIES_UPDATE) && (
              <DropdownMenuItem onSelect={() => setIsEditing(true)}>
                <Edit2Icon className="size-3" />
                <span>{tPages(($) => $.main.categories.includes.columns.edit)}</span>
              </DropdownMenuItem>
            )}

            {userCan(TenantPermission.CATEGORIES_DELETE) && (
              <DropdownMenuItem
                variant="destructive"
                onSelect={() => {
                  openConfirmation({
                    title: tPages(($) => $.main.categories.includes.columns.areYouSureYouWantToDeleteThisModel, {
                      model: tPages(($) => $.main.categories.includes.columns.category),
                    }),
                    description:
                      (category.isRegular ? tPages(($) => $.main.categories.includes.columns.thisIsMarkedAsRegular) + '\n' : '') +
                      tPages(($) => $.main.categories.includes.columns.thisActionCannotBeUndone),
                    actionLabel: tPages(($) => $.main.categories.includes.columns.delete),
                    actionVariant: 'destructive',
                    cancelLabel: tPages(($) => $.main.categories.includes.columns.cancel),
                    onAction: () => {
                      router.visit(CategoryController.destroy(category.id), {
                        preserveState: true,
                        preserveScroll: true,
                      });
                    },
                  });
                }}
              >
                <Trash2Icon className="size-3" />
                <span>{tPages(($) => $.main.categories.includes.columns.delete)}</span>
              </DropdownMenuItem>
            )}
          </DatatableCellActions>
        </>
      );
    },
  },
];
