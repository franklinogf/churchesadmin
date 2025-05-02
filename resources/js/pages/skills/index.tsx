import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { InputField } from '@/components/forms/inputs/InputField';
import { SwitchField } from '@/components/forms/inputs/SwitchField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
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
      const skill = row.original;
      if (skill.isRegular && !userCan(UserPermission.UPDATE_REGULAR_TAG) && !userCan(UserPermission.DELETE_REGULAR_TAG)) {
        return null;
      }

      if (!userCan(UserPermission.UPDATE_SKILLS) && !userCan(UserPermission.DELETE_SKILLS)) {
        return null;
      }

      return (
        <DropdownMenu>
          <DropdownMenuTrigger asChild>
            <Button variant="ghost" size="sm">
              <MoreHorizontalIcon />
              <span className="sr-only">{t('Actions')}</span>
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent>
            {userCan(UserPermission.UPDATE_SKILLS) && (
              <SkillForm skill={skill}>
                <DropdownMenuItem onSelect={(e) => e.preventDefault()}>
                  <Edit2Icon className="size-3" />
                  <span>{t('Edit')}</span>
                </DropdownMenuItem>
              </SkillForm>
            )}
            {userCan(UserPermission.DELETE_SKILLS) && (
              <DropdownMenuItem
                variant="destructive"
                onClick={() => {
                  openConfirmation({
                    title: t('Are you sure you want to delete this skill?'),
                    description: (skill.isRegular ? t('This is marked as regular.') + '\n' : '') + t('This action cannot be undone.'),
                    actionLabel: t('Delete'),
                    actionVariant: 'destructive',
                    cancelLabel: t('Cancel'),
                    onAction: () => {
                      router.delete(route('skills.destroy', skill.id), {
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
      );
    },
  },
];

interface IndexPageProps {
  skills: Tag[];
}
export default function Index({ skills }: IndexPageProps) {
  const { t } = useLaravelReactI18n();
  const { can: userCan } = useUser();

  return (
    <AppLayout breadcrumbs={[{ title: t('Skills') }]} title={t('Skills')}>
      <PageTitle>{t('Skills')}</PageTitle>
      <div className="mx-auto w-full max-w-3xl">
        <DataTable
          headerButton={
            userCan(UserPermission.CREATE_SKILLS) && (
              <SkillForm>
                <Button>{t('Add skill')}</Button>
              </SkillForm>
            )
          }
          columns={columns}
          data={skills}
          rowId="id"
        />
      </div>
    </AppLayout>
  );
}

function SkillForm({ skill, children }: { skill?: Tag; children: React.ReactNode }) {
  const [open, setOpen] = useState(false);
  const { t } = useLaravelReactI18n();
  const { can: userCan } = useUser();
  const { data, setData, post, put, errors, reset, processing } = useForm({
    name: skill?.name ?? '',
    is_regular: skill?.isRegular ?? false,
  });

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    if (skill) {
      put(route('skills.update', skill.id), {
        onSuccess: () => {
          setOpen(false);
        },
      });
    } else {
      post(route('skills.store'), {
        onSuccess: () => {
          setOpen(false);
          reset();
        },
      });
    }
  }
  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger asChild>{children}</DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>{skill ? t('Edit Skill') : t('Add Skill')}</DialogTitle>
          <DialogDescription hidden></DialogDescription>
        </DialogHeader>

        <form className="space-y-4" onSubmit={handleSubmit}>
          <InputField required label={t('Name')} value={data.name} onChange={(value) => setData(`name`, value)} error={errors.name} />

          {userCan(skill ? UserPermission.UPDATE_REGULAR_TAG : UserPermission.CREATE_REGULAR_TAG) && (
            <SwitchField
              description={t('Only admins would be allowed to edit and delete this skill')}
              label={t('Mark this skill as regular')}
              value={data.is_regular}
              onChange={(value) => setData('is_regular', value)}
              error={errors.is_regular}
            />
          )}
          <div className="flex justify-end">
            <SubmitButton isSubmitting={processing}>{t('Save')}</SubmitButton>
          </div>
        </form>
      </DialogContent>
    </Dialog>
  );
}
