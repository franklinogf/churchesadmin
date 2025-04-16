import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { UserPermission } from '@/enums/user';
import { useUser } from '@/hooks/use-permissions';
import useConfirmationStore from '@/stores/confirmationStore';
import { Member } from '@/types/models/member';
import { Link, router } from '@inertiajs/react';
import { ColumnDef } from '@tanstack/react-table';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { Edit2Icon, MoreHorizontalIcon, Trash2Icon, User2Icon } from 'lucide-react';

export const columns: ColumnDef<Member>[] = [
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Name" />,
    enableHiding: false,
    accessorKey: 'name',
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Last name" />,
    enableHiding: false,
    accessorKey: 'lastName',
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Phone" />,
    accessorKey: 'phone',
    enableSorting: false,
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Gender" />,
    accessorKey: 'gender',
    cell: ({ row }) => {
      return <Badge className="w-24">{row.getValue('gender')}</Badge>;
    },
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Civil Status" />,
    accessorKey: 'civilStatus',
    cell: ({ row }) => {
      return <Badge className="w-24">{row.getValue('civilStatus')}</Badge>;
    },
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
      return (
        <DropdownMenu>
          <DropdownMenuTrigger asChild>
            <Button variant="ghost" size="sm">
              <span className="sr-only">{t('Actions')}</span>
              <MoreHorizontalIcon />
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent>
            <DropdownMenuItem asChild>
              <Link href={route('members.show', row.original.id)}>
                <User2Icon className="size-3" />
                <span>{t('View')}</span>
              </Link>
            </DropdownMenuItem>
            {userCan(UserPermission.UPDATE_MEMBERS) && (
              <DropdownMenuItem asChild>
                <Link href={route('members.edit', row.original.id)}>
                  <Edit2Icon className="size-3" />
                  <span>{t('Edit')}</span>
                </Link>
              </DropdownMenuItem>
            )}
            {userCan(UserPermission.DELETE_MEMBERS) && (
              <DropdownMenuItem
                variant="destructive"
                onClick={() => {
                  openConfirmation({
                    title: t('Are you sure you want to delete this member?'),
                    description: t('You can restore it any time.'),
                    actionLabel: t('Delete'),
                    actionVariant: 'destructive',
                    cancelLabel: t('Cancel'),
                    onAction: () => {
                      router.delete(route('members.destroy', row.original.id), {
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
