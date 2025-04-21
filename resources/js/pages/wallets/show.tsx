import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import { Badge } from '@/components/ui/badge';
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
        title: wallet.name,
      },
    ],
    [wallet.name, t],
  );

  return (
    <AppLayout title={t('Wallet :name', { name: wallet.name })} breadcrumbs={breadcrumbs}>
      <div className="flex flex-col items-center gap-4">
        <PageTitle>{wallet.name}</PageTitle>
        <Badge className="text-xl" variant="brand">
          ${wallet.balanceFloat}
        </Badge>
      </div>
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
