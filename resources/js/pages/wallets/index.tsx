import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import { type Wallet } from '@/types/models/wallet';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { walletColumns } from './includes/walletColumns';

import { Button } from '@/components/ui/button';
import { WalletForm } from './components/WalletForm';

interface IndexPageProps {
  wallets: Wallet[];
}

export default function Index({ wallets }: IndexPageProps) {
  const { t } = useLaravelReactI18n();

  return (
    <AppLayout breadcrumbs={[{ title: t('Wallets') }]} title={t('Wallets')}>
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
