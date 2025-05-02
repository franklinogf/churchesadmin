import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { InputField } from '@/components/forms/inputs/InputField';
import { SwitchField } from '@/components/forms/inputs/SwitchField';
import { PageTitle } from '@/components/PageTitle';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { UserPermission } from '@/enums/user';
import { useUser } from '@/hooks/use-permissions';
import AppLayout from '@/layouts/app-layout';
import useConfirmationStore from '@/stores/confirmationStore';
import { type Tag } from '@/types/models/tag';
import { router, useForm } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { Edit2Icon, MoreHorizontalIcon, Trash2Icon } from 'lucide-react';
import { useState } from 'react';

export const columns: ColumnDef<Tag>[] = [
  {
    enableHiding: false,
    header: 'Name',
    accessorKey: 'name',
  },
  {
    id: 'actions',
    enableHiding: false,
    enableSorting: false,
    size: 0,
    cell: function CellComponent({ row }) {
      const { t } = useLaravelReactI18n();
      const { openConfirmation } = useConfirmationStore();
      const { can: userCan } = useUser();
      const [isEditing, setIsEditing] = useState(false);
      const category = row.original;
      if (category.isRegular && !userCan(UserPermission.UPDATE_REGULAR_TAG) && !userCan(UserPermission.DELETE_REGULAR_TAG)) {
        return null;
      }

      if (!userCan(UserPermission.UPDATE_CATEGORIES) && !userCan(UserPermission.DELETE_CATEGORIES)) {
        return null;
      }

      return (
        <>
          <CategoryForm category={category} open={isEditing} setOpen={setIsEditing} />
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="ghost" size="sm">
                <MoreHorizontalIcon />
                <span className="sr-only">{t('Actions')}</span>
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent>
              {userCan(UserPermission.UPDATE_CATEGORIES) && (
                <DropdownMenuItem onSelect={() => setIsEditing(true)}>
                  <Edit2Icon className="size-3" />
                  <span>{t('Edit')}</span>
                </DropdownMenuItem>
              )}

              {userCan(UserPermission.DELETE_CATEGORIES) && (
                <DropdownMenuItem
                  variant="destructive"
                  onClick={() => {
                    openConfirmation({
                      title: t('Are you sure you want to delete this category?'),
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
            </DropdownMenuContent>
          </DropdownMenu>
        </>
      );
    },
  },
];

interface IndexPageProps {
  categories: Tag[];
}
export default function Index({ categories }: IndexPageProps) {
  const { t } = useLaravelReactI18n();
  const { can: userCan } = useUser();
  const [open, setOpen] = useState(false);
  return (
    <AppLayout breadcrumbs={[{ title: t('Categories') }]} title={t('Categories')}>
      <PageTitle>{t('Categories')}</PageTitle>
      <div className="mx-auto w-full max-w-3xl">
        <CategoryForm open={open} setOpen={setOpen} />
        <DataTable
          headerButton={
            userCan(UserPermission.CREATE_CATEGORIES) && (
              <Button onClick={() => setOpen(true)} size="sm">
                {t('Add Category')}
              </Button>
            )
          }
          columns={columns}
          data={categories}
          rowId="id"
        />
      </div>
    </AppLayout>
  );
}

function CategoryForm({ category, open, setOpen }: { category?: Tag; open: boolean; setOpen: (open: boolean) => void }) {
  const { t } = useLaravelReactI18n();
  const { can: userCan } = useUser();
  const { data, setData, post, put, errors, reset, processing } = useForm({
    name: category?.name ?? '',
    is_regular: category?.isRegular ?? false,
  });

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    if (category) {
      put(route('categories.update', category.id), {
        onSuccess: () => {
          setOpen(false);
        },
      });
    } else {
      post(route('categories.store'), {
        onSuccess: () => {
          setOpen(false);
          reset();
        },
      });
    }
  }

  return (
    <ResponsiveModal open={open} setOpen={setOpen} title="Add Category">
      <form className="space-y-4" onSubmit={handleSubmit}>
        <InputField label={t('Name')} value={data.name} onChange={(value) => setData(`name`, value)} error={errors.name} />
        {userCan(category ? UserPermission.UPDATE_REGULAR_TAG : UserPermission.CREATE_REGULAR_TAG) && (
          <SwitchField
            description={t('Only admins would be allowed to edit and delete this category')}
            label={t('Mark this category as regular')}
            value={data.is_regular}
            onChange={(value) => setData('is_regular', value)}
            error={errors.is_regular}
          />
        )}
        <ResponsiveModalFooterSubmit isSubmitting={processing} label={t('Save')} />
      </form>
    </ResponsiveModal>
  );
}
