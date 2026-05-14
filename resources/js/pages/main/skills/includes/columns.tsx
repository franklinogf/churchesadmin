import SkillController from '@/actions/App/Http/Controllers/SkillController';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { SkillForm } from '@/components/forms/skill-form';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import useConfirmationStore from '@/stores/confirmation-store';
import { type Tag } from '@/types/models/tag';
import { router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, MoreHorizontalIcon, Trash2Icon } from 'lucide-react';
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
      const skill = row.original;
      if (skill.isRegular && !userCan(TenantPermission.REGULAR_TAGS_UPDATE) && !userCan(TenantPermission.REGULAR_TAGS_DELETE)) {
        return null;
      }

      if (!userCan(TenantPermission.SKILLS_UPDATE) && !userCan(TenantPermission.SKILLS_DELETE)) {
        return null;
      }

      return (
        <>
          <SkillForm skill={skill} open={isEditing} setOpen={setIsEditing} />
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="ghost" size="sm">
                <MoreHorizontalIcon />
                <span className="sr-only">{tPages(($) => $.main.skills.includes.columns.actions)}</span>
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent>
              {userCan(TenantPermission.SKILLS_UPDATE) && (
                <DropdownMenuItem onSelect={() => setIsEditing(true)}>
                  <Edit2Icon className="size-3" />
                  <span>{tPages(($) => $.main.skills.includes.columns.edit)}</span>
                </DropdownMenuItem>
              )}
              {userCan(TenantPermission.SKILLS_DELETE) && (
                <DropdownMenuItem
                  variant="destructive"
                  onClick={() => {
                    openConfirmation({
                      title: tPages(($) => $.main.skills.includes.columns.areYouSureYouWantToDeleteThisModel, {
                        model: tPages(($) => $.main.skills.includes.columns.skill),
                      }),
                      description:
                        (skill.isRegular ? tPages(($) => $.main.skills.includes.columns.thisIsMarkedAsRegular) + '\n' : '') +
                        tPages(($) => $.main.skills.includes.columns.thisActionCannotBeUndone),
                      actionLabel: tPages(($) => $.main.skills.includes.columns.delete),
                      actionVariant: 'destructive',
                      cancelLabel: tPages(($) => $.main.skills.includes.columns.cancel),
                      onAction: () => {
                        router.visit(SkillController.destroy(skill.id), {
                          preserveState: true,
                          preserveScroll: true,
                        });
                      },
                    });
                  }}
                >
                  <Trash2Icon className="size-3" />
                  <span>{tPages(($) => $.main.skills.includes.columns.delete)}</span>
                </DropdownMenuItem>
              )}
            </DropdownMenuContent>
          </DropdownMenu>
        </>
      );
    },
  },
];
