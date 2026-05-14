import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import { type Wallet } from '@/types/models/wallet';
import { useTranslation } from 'react-i18next';
import { walletColumns } from './includes/walletColumns';

import Datatable from '@/components/datatable/datatable';
import { WalletForm } from '@/components/forms/wallet-form';
import { Button } from '@/components/ui/button';
import { useState } from 'react';

interface IndexPageProps {
  wallets: Wallet[];
}

export default function Index({ wallets }: IndexPageProps) {
  const { t: tPages } = useTranslation('pages');
  const [open, setOpen] = useState(false);

  return (
    <AppLayout breadcrumbs={[{ title: tPages(($) => $.wallets.index.wallets) }]} title={tPages(($) => $.wallets.index.wallets)}>
      <PageTitle>{tPages(($) => $.wallets.index.wallets)}</PageTitle>
      <WalletForm open={open} setOpen={setOpen} />
      <section className="mx-auto mt-8 w-full max-w-2xl">
        <Datatable
          renderLeftTop={
            <Button
              size="sm"
              onClick={() => {
                setOpen(true);
              }}
            >
              {tPages(($) => $.wallets.index.addModel, { model: tPages(($) => $.wallets.index.wallet) })}
            </Button>
          }
          data={wallets}
          columns={walletColumns}
        />
      </section>
    </AppLayout>
  );
}
