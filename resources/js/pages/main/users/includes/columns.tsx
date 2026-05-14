import UserController from '@/actions/App/Http/Controllers/UserController';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/datatable-actions-dropdown';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { Badge } from '@/components/ui/badge';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import useConfirmationStore from '@/stores/confirmation-store';
import { type Role, type User } from '@/types/models/user';
import { Link, router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, Trash2Icon } from 'lucide-react';
import { useTranslation } from 'react-i18next';

export const columns: ColumnDef<User>[] = [
  {
    header: ({ column }) => <DatatableHeader column={column} title="Name" />,
    enableHiding: false,
    accessorKey: 'name',
  },
  {
    header: ({ column }) => <DatatableHeader justify="center" column={column} title="Email" />,
    enableHiding: false,
    accessorKey: 'email',
    cell: ({ row }) => <DatatableCell justify="center">{row.getValue('email')}</DatatableCell>,
  },
  {
    header: ({ column }) => <DatatableHeader justify="center" column={column} title="Role" />,
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
      const { t: tPages } = useTranslation('pages');
      const { openConfirmation } = useConfirmationStore();
      const { can: userCan } = useUser();
      const user = row.original;

      if (!userCan(TenantPermission.USERS_UPDATE) && !userCan(TenantPermission.USERS_DELETE)) {
        return null;
      }

      return (
        <DatatableActionsDropdown>
          {userCan(TenantPermission.USERS_UPDATE) && (
            <DropdownMenuItem asChild>
              <Link href={UserController.edit(user.id)}>
                <Edit2Icon className="size-3" />
                <span>{tPages(($) => $.main.users.includes.columns.edit)}</span>
              </Link>
            </DropdownMenuItem>
          )}
          {userCan(TenantPermission.USERS_DELETE) && (
            <DropdownMenuItem
              variant="destructive"
              onClick={() => {
                openConfirmation({
                  title: tPages(($) => $.main.users.includes.columns.areYouSureYouWantToDeleteThisModel, {
                    model: tPages(($) => $.main.users.includes.columns.user),
                  }),
                  description: tPages(($) => $.main.users.includes.columns.youCanRestoreItAnyTime),
                  actionLabel: tPages(($) => $.main.users.includes.columns.delete),
                  actionVariant: 'destructive',
                  cancelLabel: tPages(($) => $.main.users.includes.columns.cancel),
                  onAction: () => {
                    router.visit(UserController.destroy(user.id), {
                      preserveState: true,
                      preserveScroll: true,
                    });
                  },
                });
              }}
            >
              <Trash2Icon className="size-3" />
              <span>{tPages(($) => $.main.users.includes.columns.delete)}</span>
            </DropdownMenuItem>
          )}
        </DatatableActionsDropdown>
      );
    },
  },
];
