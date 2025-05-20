import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import { type Wallet } from '@/types/models/wallet';
import { walletColumns } from './includes/walletColumns';

import { WalletForm } from '@/components/forms/wallet-form';
import { Button } from '@/components/ui/button';
import { useState } from 'react';

interface IndexPageProps {
  wallets: Wallet[];
}

export default function Index({ wallets }: IndexPageProps) {
  const { t } = useTranslations();
  const [open, setOpen] = useState(false);

  return (
    <AppLayout breadcrumbs={[{ title: t('Wallets') }]} title={t('Wallets')}>
      <PageTitle>{t('Wallets')}</PageTitle>
      <WalletForm open={open} setOpen={setOpen} />
      <section className="mx-auto mt-8 w-full max-w-2xl">
        <DataTable
          headerButton={
            <Button
              size="sm"
              onClick={() => {
                setOpen(true);
              }}
            >
              {t('Add :model', { model: t('Wallet') })}
            </Button>
          }
          data={wallets}
          columns={walletColumns}
        />
      </section>
    </AppLayout>
  );
}
