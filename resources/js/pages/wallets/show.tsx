import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import type { Wallet } from '@/types/models/wallet';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { useMemo } from 'react';
import { transactionColumns } from './includes/transactionColumns';

export default function Show({ wallet }: { wallet: Wallet }) {
  const { t } = useLaravelReactI18n();

  const breadcrumbs: BreadcrumbItem[] = useMemo(
    () => [
      {
        title: t('Wallets'),
        href: route('wallets.index'),
      },
      {
        title: t('Transactions of :name', { name: wallet.name }),
      },
    ],
    [wallet, t],
  );

  return (
    <AppLayout title={t('Wallet :name', { name: wallet.name })} breadcrumbs={breadcrumbs}>
      <PageTitle>{wallet.name}</PageTitle>
      {wallet.transactions ? (
        <DataTable
          data={wallet.transactions}
          columns={transactionColumns}
          sortingState={[{ id: 'createdAt', desc: true }]}
          visibilityState={{ confirmed: false }}
        />
      ) : null}
    </AppLayout>
  );
}
