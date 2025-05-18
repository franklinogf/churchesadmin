import { SkillForm } from '@/components/forms/skill-form';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { UserPermission } from '@/enums/user';
import { useUser } from '@/hooks/use-permissions';
import useConfirmationStore from '@/stores/confirmationStore';
import { type Tag } from '@/types/models/tag';
import { router } from '@inertiajs/react';
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
      const skill = row.original;
      if (skill.isRegular && !userCan(UserPermission.UPDATE_REGULAR_TAG) && !userCan(UserPermission.DELETE_REGULAR_TAG)) {
        return null;
      }

      if (!userCan(UserPermission.UPDATE_SKILLS) && !userCan(UserPermission.DELETE_SKILLS)) {
        return null;
      }

      return (
        <>
          <SkillForm skill={skill} open={isEditing} setOpen={setIsEditing} />
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="ghost" size="sm">
                <MoreHorizontalIcon />
                <span className="sr-only">{t('Actions')}</span>
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent>
              {userCan(UserPermission.UPDATE_SKILLS) && (
                <DropdownMenuItem onSelect={() => setIsEditing(true)}>
                  <Edit2Icon className="size-3" />
                  <span>{t('Edit')}</span>
                </DropdownMenuItem>
              )}
              {userCan(UserPermission.DELETE_SKILLS) && (
                <DropdownMenuItem
                  variant="destructive"
                  onClick={() => {
                    openConfirmation({
                      title: t('Are you sure you want to delete this :model', { model: t('Skill') }),
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
        </>
      );
    },
  },
];
