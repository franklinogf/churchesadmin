import WalletCheckLayoutController from '@/actions/App/Http/Controllers/WalletCheckLayoutController';
import WalletController from '@/actions/App/Http/Controllers/WalletController';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/datatable-actions-dropdown';
import { DatatableCellBoolean } from '@/components/custom-ui/datatable/datatable-cell-boolean';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { WalletForm } from '@/components/forms/wallet-form';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { HoverCard, HoverCardContent, HoverCardTrigger } from '@/components/ui/hover-card';
import { WalletName } from '@/enums/WalletName';
import useConfirmationStore from '@/stores/confirmation-store';
import type { Wallet } from '@/types/models/wallet';
import { Link, router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { ArchiveIcon, ArchiveRestoreIcon, Edit2Icon, FilePenIcon, WalletIcon } from 'lucide-react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';

export const walletColumns: ColumnDef<Wallet>[] = [
  {
    enableHiding: false,
    enableSorting: false,
    header: ({ column }) => <DatatableHeader column={column} title="Name" />,
    accessorKey: 'name',
    cell: function CellComponent({ row }) {
      const wallet = row.original;
      return (
        <HoverCard>
          <HoverCardTrigger>{wallet.name}</HoverCardTrigger>
          <HoverCardContent>
            <div className="flex flex-col gap-2">
              <span className="text-sm font-semibold">{wallet.name}</span>
              {wallet.bankName && (
                <span className="text-muted-foreground text-sm">
                  {wallet.bankName} - {wallet.bankAccountNumber}
                </span>
              )}
              {wallet.description && <p className="text-muted-foreground text-sm">{wallet.description}</p>}
            </div>
          </HoverCardContent>
        </HoverCard>
      );
    },
  },
  {
    enableHiding: false,
    accessorKey: 'balanceFloat',
    header: ({ column }) => <DatatableHeader column={column} title="Balance" />,
    cell: ({ row }) => <DatatableCell justify="end">${row.original.balanceFloat}</DatatableCell>,
  },
  {
    sortingFn: (rowA, rowB) => (rowA.original.deletedAt ? 1 : 0) - (rowB.original.deletedAt ? 1 : 0),
    accessorKey: 'deletedAt',
    header: ({ column }) => <DatatableHeader column={column} title="Active" />,
    cell: ({ row }) => <DatatableCellBoolean trueCondition={row.original.deletedAt === null} />,
  },
  {
    accessorKey: 'checkLayout',
    header: ({ column }) => <DatatableHeader column={column} title="Check layout" />,
    cell: ({ row }) => <DatatableCellBoolean trueCondition={row.original.checkLayout !== null} />,
  },
  {
    id: 'actions',
    enableHiding: false,
    enableSorting: false,
    size: 0,
    cell: function CellComponent({ row }) {
      const { t: tPages } = useTranslation('pages');
      const { openConfirmation } = useConfirmationStore();
      const [isEditing, setIsEditing] = useState(false);
      //   const { can: userCan } = useUser();
      const wallet = row.original;

      return (
        <>
          <WalletForm open={isEditing} wallet={wallet} setOpen={setIsEditing} />
          <DatatableActionsDropdown>
            <DropdownMenuItem asChild>
              <Link href={WalletController.show(wallet.id)}>
                <WalletIcon className="size-3" />
                <span>{tPages(($) => $.wallets.includes.walletColumns.transactions)}</span>
              </Link>
            </DropdownMenuItem>
            <DropdownMenuItem asChild>
              <Link href={WalletCheckLayoutController.edit(wallet.id)}>
                <FilePenIcon className="size-3" />
                <span>{tPages(($) => $.wallets.includes.walletColumns.checkLayout)}</span>
              </Link>
            </DropdownMenuItem>
            {/* {userCan(UserPermission.UPDATE_SKILLS) && ( */}

            <DropdownMenuItem onSelect={() => setIsEditing(true)}>
              <Edit2Icon className="size-3" />
              <span>{tPages(($) => $.wallets.includes.walletColumns.edit)}</span>
            </DropdownMenuItem>
            {/* )} */}
            {wallet.slug !== WalletName.PRIMARY &&
              (wallet.deletedAt !== null ? (
                <DropdownMenuItem
                  onClick={() => {
                    openConfirmation({
                      title: tPages(($) => $.wallets.includes.walletColumns.areYouSureYouWantToActivateThisModel, {
                        model: tPages(($) => $.wallets.includes.walletColumns.wallet),
                      }),
                      description: tPages(($) => $.wallets.includes.walletColumns.thisWalletWillBeUsableAgain),
                      actionLabel: tPages(($) => $.wallets.includes.walletColumns.activate),
                      cancelLabel: tPages(($) => $.wallets.includes.walletColumns.cancel),
                      onAction: () => {
                        router.put(WalletController.restore(wallet.id), {
                          preserveScroll: true,
                        });
                      },
                    });
                  }}
                >
                  <ArchiveRestoreIcon className="size-3" />
                  <span>{tPages(($) => $.wallets.includes.walletColumns.activate)}</span>
                </DropdownMenuItem>
              ) : (
                <DropdownMenuItem
                  variant="destructive"
                  onClick={() => {
                    openConfirmation({
                      title: tPages(($) => $.wallets.includes.walletColumns.areYouSureYouWantToDeactivateThisModel, {
                        model: tPages(($) => $.wallets.includes.walletColumns.wallet),
                      }),
                      description: tPages(($) => $.wallets.includes.walletColumns.thisWalletWontBeUsableUntilItIsActivated),
                      actionLabel: tPages(($) => $.wallets.includes.walletColumns.deactivate),
                      actionVariant: 'destructive',
                      cancelLabel: tPages(($) => $.wallets.includes.walletColumns.cancel),
                      onAction: () => {
                        router.delete(WalletController.destroy(wallet.id), {
                          preserveScroll: true,
                        });
                      },
                    });
                  }}
                >
                  <ArchiveIcon className="size-3" />
                  <span>{tPages(($) => $.wallets.includes.walletColumns.deactivate)}</span>
                </DropdownMenuItem>
              ))}
          </DatatableActionsDropdown>
        </>
      );
    },
  },
];
