import WalletController from '@/actions/App/Http/Controllers/WalletController';
import Datatable from '@/components/datatable/datatable';
import { PageTitle } from '@/components/PageTitle';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import type { MakeRequired } from '@/types/generics';
import type { Transaction } from '@/types/models/transaction';
import type { Wallet } from '@/types/models/wallet';
import { useMemo } from 'react';
import { useTranslation } from 'react-i18next';
import { transactionColumns } from './includes/transactionColumns';

export default function Show({ wallet, initialRow }: { wallet: MakeRequired<Wallet, 'transactions'>; initialRow: Transaction | null }) {
  const { t: tPages } = useTranslation('pages');
  const breadcrumbs: BreadcrumbItem[] = useMemo(
    () => [
      {
        title: tPages(($) => $.wallets.show.wallets),
        href: WalletController.index().url,
      },
      {
        title: wallet.name,
      },
    ],
    [wallet.name, tPages],
  );
  const walletTransactions = initialRow ? [initialRow, ...wallet.transactions] : wallet.transactions;
  return (
    <AppLayout title={tPages(($) => $.wallets.show.walletName, { name: wallet.name })} breadcrumbs={breadcrumbs}>
      <div className="flex flex-col items-center gap-4">
        <PageTitle>{wallet.name}</PageTitle>
        <Badge className="text-xl" variant="brand">
          ${wallet.balanceFloat}
        </Badge>
      </div>
      {wallet.transactions ? (
        <Datatable
          data={walletTransactions}
          columns={transactionColumns}
          sortingState={[{ id: 'createdAt', desc: true }]}
          visibilityState={{ confirmed: false }}
        />
      ) : null}
    </AppLayout>
  );
}
