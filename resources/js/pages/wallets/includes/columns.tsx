import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import useConfirmationStore from '@/stores/confirmationStore';
import type { Wallet } from '@/types/models/wallet';
import { Link, router } from '@inertiajs/react';
import { ColumnDef } from '@tanstack/react-table';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { MoreHorizontalIcon, Trash2Icon, WalletIcon } from 'lucide-react';

export const columns: ColumnDef<Wallet>[] = [
  {
    enableHiding: false,
    header: 'Name',
    accessorKey: 'name',
  },
  {
    enableHiding: false,
    accessorKey: 'balanceFloat',
    header: 'Balance',
    cell: function CellComponent({ row }) {
      return <span className="flex items-center gap-0.5">${row.original.balanceFloat}</span>;
    },
  },
  {
    accessorKey: 'description',
    header: 'Description',
  },
  {
    id: 'actions',
    enableHiding: false,
    enableSorting: false,
    size: 0,
    cell: function CellComponent({ row }) {
      const { t } = useLaravelReactI18n();
      const { openConfirmation } = useConfirmationStore();
      //   const { can: userCan } = useUser();
      const wallet = row.original;

      return (
        <DropdownMenu>
          <DropdownMenuTrigger asChild>
            <Button variant="ghost" size="sm">
              <MoreHorizontalIcon />
              <span className="sr-only">{t('Actions')}</span>
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent>
            <DropdownMenuItem asChild>
              <Link href={route('wallets.show', wallet.uuid)}>
                <WalletIcon className="size-3" />
                <span>{t('View')}</span>
              </Link>
            </DropdownMenuItem>
            {/* {userCan(UserPermission.UPDATE_SKILLS) && (
              <SkillForm skill={skill}>
                <DropdownMenuItem onSelect={(e) => e.preventDefault()}>
                  <Edit2Icon className="size-3" />
                  <span>{t('Edit')}</span>
                </DropdownMenuItem>
              </SkillForm>
            )} */}
            {/* {userCan(UserPermission.DELETE_SKILLS) && ( */}
            <DropdownMenuItem
              variant="destructive"
              onClick={() => {
                openConfirmation({
                  title: t('Are you sure you want to delete this wallet?'),
                  description: t('This action cannot be undone.'),
                  actionLabel: t('Delete'),
                  actionVariant: 'destructive',
                  cancelLabel: t('Cancel'),
                  onAction: () => {
                    router.delete(route('wallets.destroy', wallet.uuid), {
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
            {/* )} */}
          </DropdownMenuContent>
        </DropdownMenu>
      );
    },
  },
];
