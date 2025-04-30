import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import type { Expense } from '@/types/models/expense';
import { type ColumnDef } from '@tanstack/react-table';

export const columns: ColumnDef<Expense>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Wallet" />,
    accessorKey: 'transaction',
    cell: ({ row }) => (
      <DatatableCell justify="center">
        <Badge variant="secondary">{row.original.transaction.wallet?.name}</Badge>
      </DatatableCell>
    ),
  },
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Expense Type" />,
    accessorKey: 'expenseType',
    cell: ({ row }) => (
      <DatatableCell justify="center">
        <Badge>{row.original.expenseType.name}</Badge>
      </DatatableCell>
    ),
  },
  {
    enableHiding: true,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Member" />,
    accessorKey: 'member',
    cell: ({ row }) => {
      const { member } = row.original;
      if (member === null) return null;
      return `${member.name} ${member.lastName}`;
    },
  },
  {
    enableHiding: false,
    accessorKey: 'amount',
    header: ({ column }) => <DataTableColumnHeader column={column} title="Amount" />,
    cell: ({ row }) => <DatatableCell justify="end">${row.original.transaction.amountFloat}</DatatableCell>,
  },
  {
    accessorKey: 'date',
    header: ({ column }) => <DataTableColumnHeader column={column} title="Date" />,
    meta: 'Date',
    cell: ({ row }) => <DatatableCell justify="center">{row.original.date}</DatatableCell>,
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
