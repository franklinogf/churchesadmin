import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { type Wallet } from '@/types/models/wallet';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { walletColumns } from './includes/walletColumns';

import { Button } from '@/components/ui/button';
import { WalletForm } from './components/WalletForm';

type WalletForm = {
  name: string;
  description: string;
  balance: string;
  bank_name: string;
  bank_account_number: string;
  bank_routing_number: string;
};

interface IndexPageProps {
  wallets: Wallet[];
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Wallets',
    href: route('wallets.index'),
  },
];

export default function Index({ wallets }: IndexPageProps) {
  const { t } = useLaravelReactI18n();
  return (
    <AppLayout breadcrumbs={breadcrumbs} title={t('Wallets')}>
      <PageTitle>{t('Wallets')}</PageTitle>
      <DataTable
        headerButton={
          <WalletForm>
            <Button size="sm">{t('Add Wallet')}</Button>
          </WalletForm>
        }
        data={wallets}
        columns={walletColumns}
      />
    </AppLayout>
  );
}
