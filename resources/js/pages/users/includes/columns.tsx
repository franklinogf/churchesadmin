import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { UserPermission } from '@/enums/user';
import { useTranslations } from '@/hooks/use-translations';
import { useUser } from '@/hooks/use-user';
import useConfirmationStore from '@/stores/confirmationStore';
import { type Role, type User } from '@/types/models/user';
import { Link, router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, MoreHorizontalIcon, Trash2Icon } from 'lucide-react';

export const columns: ColumnDef<User>[] = [
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Name" />,
    enableHiding: false,
    accessorKey: 'name',
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Email" />,
    enableHiding: false,
    accessorKey: 'email',
    cell: ({ row }) => <DatatableCell justify="center">{row.getValue('email')}</DatatableCell>,
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Role" />,
    enableHiding: false,
    accessorKey: 'roles',
    cell: ({ row }) => {
      const roles = row.getValue('roles') as Role[];
      return (
        <DatatableCell justify="center">
          <Badge className="flex items-center gap-2">
            {roles[0]?.label}
            {roles.length > 1 && <span className="text-xs font-medium">+{roles.length - 1}</span>}
          </Badge>
        </DatatableCell>
      );
    },
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
      const user = row.original;

      if (!userCan(UserPermission.USERS_UPDATE) && !userCan(UserPermission.USERS_DELETE)) {
        return null;
      }

      return (
        <DropdownMenu>
          <DropdownMenuTrigger asChild>
            <Button variant="ghost" size="sm">
              <span className="sr-only">{t('Actions')}</span>
              <MoreHorizontalIcon />
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent>
            {userCan(UserPermission.USERS_UPDATE) && (
              <DropdownMenuItem asChild>
                <Link href={route('users.edit', user.id)}>
                  <Edit2Icon className="size-3" />
                  <span>{t('Edit')}</span>
                </Link>
              </DropdownMenuItem>
            )}
            {userCan(UserPermission.USERS_DELETE) && (
              <DropdownMenuItem
                variant="destructive"
                onClick={() => {
                  openConfirmation({
                    title: t('Are you sure you want to delete this :model?', { model: t('User') }),
                    description: t('You can restore it any time.'),
                    actionLabel: t('Delete'),
                    actionVariant: 'destructive',
                    cancelLabel: t('Cancel'),
                    onAction: () => {
                      router.delete(route('users.destroy', user.id), {
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
