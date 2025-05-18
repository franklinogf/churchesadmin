import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { UserPermission } from '@/enums/user';
import { useUser } from '@/hooks/use-permissions';

import useConfirmationStore from '@/stores/confirmationStore';
import { type Missionary } from '@/types/models/missionary';
import { Link, router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { Edit2Icon, MoreHorizontalIcon, Trash2Icon, User2Icon } from 'lucide-react';

export const columns: ColumnDef<Missionary>[] = [
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
    cell: ({ row }) => {
      return <DatatableCell justify="center">{row.getValue('phone')}</DatatableCell>;
    },
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Gender" />,
    accessorKey: 'gender',
    cell: function CellComponent({ row }) {
      const { t } = useLaravelReactI18n();
      return (
        <DatatableCell justify="center">
          <Badge className="w-24">{t(`enum.gender.${row.getValue('gender')}`)}</Badge>
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
              <Link href={route('missionaries.show', row.original.id)}>
                <User2Icon className="size-3" />
                <span>{t('View')}</span>
              </Link>
            </DropdownMenuItem>
            {userCan(UserPermission.UPDATE_MISSIONARIES) && (
              <DropdownMenuItem asChild>
                <Link href={route('missionaries.edit', row.original.id)}>
                  <Edit2Icon className="size-3" />
                  <span>{t('Edit')}</span>
                </Link>
              </DropdownMenuItem>
            )}
            {userCan(UserPermission.DELETE_MISSIONARIES) && (
              <DropdownMenuItem
                variant="destructive"
                onClick={() => {
                  openConfirmation({
                    title: t('Are you sure you want to delete this :model', { model: t('Missionary') }),
                    description: t('You can restore it any time.'),
                    actionLabel: t('Delete'),
                    actionVariant: 'destructive',
                    cancelLabel: t('Cancel'),
                    onAction: () => {
                      router.delete(route('missionaries.destroy', row.original.id));
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
