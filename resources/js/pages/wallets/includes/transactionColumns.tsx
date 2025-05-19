import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { useTranslations } from '@/hooks/use-translations';
import type { Transaction } from '@/types/models/transaction';
import { type ColumnDef } from '@tanstack/react-table';
import { CheckIcon, XCircleIcon } from 'lucide-react';

export const transactionColumns: ColumnDef<Transaction>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Type" />,
    accessorKey: 'type',
    cell: function CellColumn({ row }) {
      const { t } = useTranslations();
      return (
        <DatatableCell justify="center">
          <Badge>{t(`enum.transaction_type.${row.original.type}`)}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="From" />,
    accessorKey: 'meta',
    cell: function CellColumn({ row }) {
      const { t } = useTranslations();
      if (!row.original.meta) return null;
      return (
        <DatatableCell justify="center">
          <Badge variant="outline">{t(`enum.transaction_meta_type.${row.original.meta.type}`)}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    enableHiding: false,
    accessorKey: 'amountFloat',
    header: ({ column }) => <DataTableColumnHeader column={column} title="Amount" />,
    cell: ({ row }) => <DatatableCell justify="end">${row.original.amountFloat}</DatatableCell>,
  },
  {
    accessorKey: 'confirmed',
    header: ({ column }) => <DataTableColumnHeader column={column} title="Confirmed" />,
    cell: ({ row }) => (
      <DatatableCell justify="center">
        {row.original.confirmed ? <CheckIcon className="size-4 text-green-600" /> : <XCircleIcon className="text-destructive size-4" />}
      </DatatableCell>
    ),
  },
  {
    accessorKey: 'createdAt',
    header: ({ column }) => <DataTableColumnHeader column={column} title="Date" />,
    meta: 'Date',
    cell: ({ row }) => <DatatableCell justify="center">{row.original.createdAt}</DatatableCell>,
  },
  //   {
  //     id: 'actions',
  //     enableHiding: false,
  //     enableSorting: false,
  //     size: 0,
  //     cell: function CellComponent({ row }) {
  //       const { t } = useLaravelReactI18n();
  //       const { openConfirmation } = useConfirmationStore();
  //       //   const { can: userCan } = useUser();
  //       const wallet = row.original;

  //       return (
  //         <DropdownMenu>
  //           <DropdownMenuTrigger asChild>
  //             <Button variant="ghost" size="sm">
  //               <MoreHorizontalIcon />
  //               <span className="sr-only">{t('Actions')}</span>
  //             </Button>
  //           </DropdownMenuTrigger>
  //           <DropdownMenuContent>
  //             <DropdownMenuItem asChild>
  //               <Link href={route('wallets.show', wallet.uuid)}>
  //                 <WalletIcon className="size-3" />
  //                 <span>{t('View')}</span>
  //               </Link>
  //             </DropdownMenuItem>
  //             {/* {userCan(UserPermission.UPDATE_SKILLS) && ( */}
  //             <WalletForm wallet={wallet}>
  //               <DropdownMenuItem onSelect={(e) => e.preventDefault()}>
  //                 <Edit2Icon className="size-3" />
  //                 <span>{t('Edit')}</span>
  //               </DropdownMenuItem>
  //             </WalletForm>
  //             {/* )} */}
  //             {wallet.slug !== WalletName.PRIMARY && wallet.deletedAt ? (
  //               <DropdownMenuItem
  //                 onClick={() => {
  //                   openConfirmation({
  //                     title: t('Are you sure you want to activate this wallet?'),
  //                     description: t('This wallet will be usable again'),
  //                     actionLabel: t('Activate'),
  //                     cancelLabel: t('Cancel'),
  //                     onAction: () => {
  //                       router.put(route('wallets.restore', wallet.uuid), {
  //                         preserveScroll: true,
  //                       });
  //                     },
  //                   });
  //                 }}
  //               >
  //                 <ArchiveRestoreIcon className="size-3" />
  //                 <span>{t('Activate')}</span>
  //               </DropdownMenuItem>
  //             ) : (
  //               <DropdownMenuItem
  //                 variant="destructive"
  //                 onClick={() => {
  //                   openConfirmation({
  //                     title: t('Are you sure you want to deactivate this wallet?'),
  //                     description: t("This wallet won't be usable until it is activated"),
  //                     actionLabel: t('Deactivate'),
  //                     actionVariant: 'destructive',
  //                     cancelLabel: t('Cancel'),
  //                     onAction: () => {
  //                       router.delete(route('wallets.destroy', wallet.uuid), {
  //                         preserveScroll: true,
  //                       });
  //                     },
  //                   });
  //                 }}
  //               >
  //                 <ArchiveIcon className="size-3" />
  //                 <span>{t('Deactivate')}</span>
  //               </DropdownMenuItem>
  //             )}
  //           </DropdownMenuContent>
  //         </DropdownMenu>
  //       );
  //     },
  //   },
];
