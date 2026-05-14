import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { Badge } from '@/components/ui/badge';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';

import MissionaryController from '@/actions/App/Http/Controllers/MissionaryController';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/datatable-actions-dropdown';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import useConfirmationStore from '@/stores/confirmation-store';
import { type Missionary } from '@/types/models/missionary';
import { Link, router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, Trash2Icon, User2Icon } from 'lucide-react';
import { useTranslation } from 'react-i18next';

export const columns: ColumnDef<Missionary>[] = [
  {
    header: ({ column }) => <DatatableHeader column={column} title="Name" />,
    enableHiding: false,
    accessorKey: 'name',
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Last name" />,
    enableHiding: false,
    accessorKey: 'lastName',
  },
  {
    header: ({ column }) => <DatatableHeader justify="center" column={column} title="Phone" />,
    accessorKey: 'phone',
    enableSorting: false,
    cell: ({ row }) => {
      return <DatatableCell justify="center">{row.getValue('phone')}</DatatableCell>;
    },
  },
  {
    header: ({ column }) => <DatatableHeader justify="center" column={column} title="Gender" />,
    accessorKey: 'gender',
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
            <Link href={MissionaryController.show(row.original.id).url}>
              <User2Icon className="size-3" />
              <span>{tPages(($) => $.main.missionaries.includes.columns.view)}</span>
            </Link>
          </DropdownMenuItem>
          {userCan(TenantPermission.MISSIONARIES_UPDATE) && (
            <DropdownMenuItem asChild>
              <Link href={MissionaryController.edit(row.original.id).url}>
                <Edit2Icon className="size-3" />
                <span>{tPages(($) => $.main.missionaries.includes.columns.edit)}</span>
              </Link>
            </DropdownMenuItem>
          )}
          {userCan(TenantPermission.MISSIONARIES_DELETE) && (
            <DropdownMenuItem
              variant="destructive"
              onClick={() => {
                openConfirmation({
                  title: tPages(($) => $.main.missionaries.includes.columns.areYouSureYouWantToDeleteThisModel, {
                    model: tPages(($) => $.main.missionaries.includes.columns.missionary),
                  }),
                  description: tPages(($) => $.main.missionaries.includes.columns.youCanRestoreItAnyTime),
                  actionLabel: tPages(($) => $.main.missionaries.includes.columns.delete),
                  actionVariant: 'destructive',
                  cancelLabel: tPages(($) => $.main.missionaries.includes.columns.cancel),
                  onAction: () => {
                    router.visit(MissionaryController.destroy(row.original.id).url);
                  },
                });
              }}
            >
              <Trash2Icon className="size-3" />
              <span>{tPages(($) => $.main.missionaries.includes.columns.delete)}</span>
            </DropdownMenuItem>
          )}
        </DatatableActionsDropdown>
      );
    },
  },
];
