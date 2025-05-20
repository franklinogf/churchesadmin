import { SkillForm } from '@/components/forms/skill-form';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { UserPermission } from '@/enums/user';
import { useTranslations } from '@/hooks/use-translations';
import { useUser } from '@/hooks/use-user';
import useConfirmationStore from '@/stores/confirmationStore';
import { type Tag } from '@/types/models/tag';
import { router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
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
      const { t } = useTranslations();
      const { openConfirmation } = useConfirmationStore();
      const { can: userCan } = useUser();
      const [isEditing, setIsEditing] = useState(false);
      const skill = row.original;
      if (skill.isRegular && !userCan(UserPermission.REGULAR_TAGS_UPDATE) && !userCan(UserPermission.REGULAR_TAGS_DELETE)) {
        return null;
      }

      if (!userCan(UserPermission.SKILLS_UPDATE) && !userCan(UserPermission.SKILLS_DELETE)) {
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
              {userCan(UserPermission.SKILLS_UPDATE) && (
                <DropdownMenuItem onSelect={() => setIsEditing(true)}>
                  <Edit2Icon className="size-3" />
                  <span>{t('Edit')}</span>
                </DropdownMenuItem>
              )}
              {userCan(UserPermission.SKILLS_DELETE) && (
                <DropdownMenuItem
                  variant="destructive"
                  onClick={() => {
                    openConfirmation({
                      title: t('Are you sure you want to delete this :model?', { model: t('Skill') }),
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
