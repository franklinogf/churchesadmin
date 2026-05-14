import MemberController from '@/actions/App/Http/Controllers/MemberController';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/datatable-actions-dropdown';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { Badge } from '@/components/ui/badge';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import useConfirmationStore from '@/stores/confirmation-store';
import { type Member } from '@/types/models/member';
import { Link, router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, Trash2Icon, User2Icon } from 'lucide-react';
import { useTranslation } from 'react-i18next';

export const columns: ColumnDef<Member>[] = [
  {
    header: ({ column }) => <DatatableHeader column={column} title="Name" />,
    accessorKey: 'name',
    enableHiding: false,
    enableColumnFilter: false,
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Last name" />,
    accessorKey: 'lastName',
    enableHiding: false,
    enableColumnFilter: false,
  },
  {
    accessorKey: 'active',
    header: ({ column }) => <DatatableHeader justify="center" column={column} title="Active" />,
    filterFn: 'equals',
    meta: { filterVariant: 'select', translationPrefix: 'datatable:boolean.' },
    size: 80,
    cell: function CellComponent({ row }) {
      const { t: tDatatable } = useTranslation('datatable');
      const active = row.getValue('active') as boolean;
      return (
        <DatatableCell justify="center">
          {active ? (
            <Badge className="bg-green-500/10 text-green-600">{tDatatable(($) => $.boolean.true)}</Badge>
          ) : (
            <Badge className="bg-red-500/10 text-red-600">{tDatatable(($) => $.boolean.false)}</Badge>
          )}
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DatatableHeader justify="center" column={column} title="Phone" />,
    accessorKey: 'phone',
    enableSorting: false,
    enableColumnFilter: false,
    cell: ({ row }) => {
      return <DatatableCell justify="center">{row.getValue('phone')}</DatatableCell>;
    },
  },
  {
    header: ({ column }) => <DatatableHeader justify="center" column={column} title="Gender" />,
    accessorKey: 'gender',
    filterFn: 'equalsString',
    meta: { filterVariant: 'select', translationPrefix: 'enum:gender.' },
    cell: function CellComponent({ row }) {
      const { t: tEnum } = useTranslation('enum');
      return (
        <DatatableCell justify="center">
          <Badge className="w-24">{tEnum(($) => $.gender[row.original.gender])}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DatatableHeader justify="center" column={column} title="Civil status" />,
    accessorKey: 'civilStatus',
    filterFn: 'equalsString',
    meta: { filterVariant: 'select', translationPrefix: 'enum:civilStatus.' },
    cell: function CellComponent({ row }) {
      const { t: tEnum } = useTranslation('enum');
      return (
        <DatatableCell justify="center">
          <Badge className="w-24">{tEnum(($) => $.civilStatus[row.original.civilStatus])}</Badge>
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
      return (
        <DatatableActionsDropdown>
          <DropdownMenuItem asChild>
            <Link href={MemberController.show(row.original.id)}>
              <User2Icon className="size-3" />
              <span>{tPages(($) => $.main.members.includes.columns.view)}</span>
            </Link>
          </DropdownMenuItem>
          {userCan(TenantPermission.MEMBERS_UPDATE) && (
            <DropdownMenuItem asChild>
              <Link href={MemberController.edit(row.original.id)}>
                <Edit2Icon className="size-3" />
                <span>{tPages(($) => $.main.members.includes.columns.edit)}</span>
              </Link>
            </DropdownMenuItem>
          )}
          {userCan(TenantPermission.MEMBERS_DELETE) && (
            <DropdownMenuItem
              variant="destructive"
              onClick={() => {
                openConfirmation({
                  title: tPages(($) => $.main.members.includes.columns.areYouSureYouWantToDeleteThisModel, {
                    model: tPages(($) => $.main.members.includes.columns.member),
                  }),
                  description: tPages(($) => $.main.members.includes.columns.youCanRestoreItAnyTime),
                  actionLabel: tPages(($) => $.main.members.includes.columns.delete),
                  actionVariant: 'destructive',
                  cancelLabel: tPages(($) => $.main.members.includes.columns.cancel),
                  onAction: () => {
                    router.visit(MemberController.destroy(row.original.id), {
                      preserveState: true,
                      preserveScroll: true,
                    });
                  },
                });
              }}
            >
              <Trash2Icon className="size-3" />
              <span>{tPages(($) => $.main.members.includes.columns.delete)}</span>
            </DropdownMenuItem>
          )}
        </DatatableActionsDropdown>
      );
    },
  },
];
