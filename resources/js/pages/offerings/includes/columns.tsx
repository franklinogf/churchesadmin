import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { HoverCard, HoverCardContent, HoverCardTrigger } from '@/components/ui/hover-card';
import { offeringTypeIsMissionary } from '@/lib/utils';
import type { Offering } from '@/types/models/offering';
import { type ColumnDef } from '@tanstack/react-table';
import { useLaravelReactI18n } from 'laravel-react-i18n';

export const columns: ColumnDef<Offering>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Donor" />,
    accessorKey: 'donor',
    cell: ({ row }) => {
      const { donor } = row.original;
      if (!donor) return null;
      return (
        <HoverCard>
          <HoverCardTrigger asChild>
            <Button variant="link" size="sm" className="px-0">
              {`${donor.name} ${donor.lastName}`}
            </Button>
          </HoverCardTrigger>
          <HoverCardContent>{donor.email}</HoverCardContent>
        </HoverCard>
      );
    },
  },
  {
    enableHiding: false,
    header: ({ column }) => <DataTableColumnHeader column={column} title="Offering Type" />,
    accessorKey: 'offeringType',
    cell: ({ row }) => (
      <DatatableCell justify="center">
        <Badge>
          {offeringTypeIsMissionary(row.original.offeringType)
            ? `${row.original.offeringType.name} ${row.original.offeringType.lastName}`
            : row.original.offeringType.name}
        </Badge>
      </DatatableCell>
    ),
  },
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
    header: ({ column }) => <DataTableColumnHeader column={column} title="Payment method" />,
    accessorKey: 'paymentMethod',
    cell: function CellComponent({ row }) {
      const { t } = useLaravelReactI18n();
      return (
        <DatatableCell justify="center">
          <Badge>{t(`enum.payment_method.${row.original.paymentMethod}`)}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    enableHiding: false,
    accessorKey: 'amountFloat',
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
