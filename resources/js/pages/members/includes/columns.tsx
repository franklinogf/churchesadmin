import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/data-table-actions-dropdown';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { UserPermission } from '@/enums/user';
import { useTranslations } from '@/hooks/use-translations';
import { useUser } from '@/hooks/use-user';
import useConfirmationStore from '@/stores/confirmationStore';
import { type Member } from '@/types/models/member';
import { Link, router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, Trash2Icon, User2Icon } from 'lucide-react';

export const columns: ColumnDef<Member>[] = [
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Name" />,
    accessorKey: 'name',
    enableHiding: false,
    enableColumnFilter: false,
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Last name" />,
    accessorKey: 'lastName',
    enableHiding: false,
    enableColumnFilter: false,
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Phone" />,
    accessorKey: 'phone',
    enableSorting: false,
    enableColumnFilter: false,
    cell: ({ row }) => {
      return <DatatableCell justify="center">{row.getValue('phone')}</DatatableCell>;
    },
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Gender" />,
    accessorKey: 'gender',
    filterFn: 'equalsString',
    meta: { filterVariant: 'select', translationPrefix: 'enum.gender.' },
    cell: function CellComponent({ row }) {
      const { t } = useTranslations();
      return (
        <DatatableCell justify="center">
          <Badge className="w-24">{t(`enum.gender.${row.original.gender}`)}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Civil status" />,
    accessorKey: 'civilStatus',
    filterFn: 'equalsString',
    meta: { filterVariant: 'select', translationPrefix: 'enum.civil_status.' },
    cell: function CellComponent({ row }) {
      const { t } = useTranslations();
      return (
        <DatatableCell justify="center">
          <Badge className="w-24">{t(`enum.civil_status.${row.original.civilStatus}`)}</Badge>
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
      return (
        <DatatableActionsDropdown>
          <DropdownMenuItem asChild>
            <Link href={route('members.show', row.original.id)}>
              <User2Icon className="size-3" />
              <span>{t('View')}</span>
            </Link>
          </DropdownMenuItem>
          {userCan(UserPermission.MEMBERS_UPDATE) && (
            <DropdownMenuItem asChild>
              <Link href={route('members.edit', row.original.id)}>
                <Edit2Icon className="size-3" />
                <span>{t('Edit')}</span>
              </Link>
            </DropdownMenuItem>
          )}
          {userCan(UserPermission.MEMBERS_DELETE) && (
            <DropdownMenuItem
              variant="destructive"
              onClick={() => {
                openConfirmation({
                  title: t('Are you sure you want to delete this :model?', { model: t('Member') }),
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
        </DatatableActionsDropdown>
      );
    },
  },
];
