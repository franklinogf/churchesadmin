import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Wallet } from '@/types/models/wallet';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { walletColumns } from './includes/walletColumns';

import { TranslatableInput } from '@/components/forms/inputs/TranslatableInputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { Dialog, DialogContent, DialogDescription, DialogHeader } from '@/components/ui/dialog';
import { useTranslations } from '@/hooks/use-empty-translations';

import { CurrencyField } from '@/components/forms/inputs/CurrencyField';
import { Button } from '@/components/ui/button';
import { useForm } from '@inertiajs/react';
import { DialogTitle, DialogTrigger } from '@radix-ui/react-dialog';
import { useState } from 'react';

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

export function WalletForm({ wallet, children }: { wallet?: Wallet; children: React.ReactNode }) {
  const [open, setOpen] = useState(false);
  const { t } = useLaravelReactI18n();
  const { emptyTranslations } = useTranslations();

  const { data, setData, post, put, errors, reset, processing } = useForm({
    name: wallet?.nameTranslations ?? emptyTranslations,
    description: wallet?.descriptionTranslations ?? emptyTranslations,
    balance: wallet?.balanceFloat ?? '0.00',
  });

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    if (wallet) {
      put(route('wallets.update', wallet.uuid), {
        onSuccess: () => {
          setOpen(false);
        },
      });
    } else {
      post(route('wallets.store'), {
        onSuccess: () => {
          setOpen(false);
          reset();
        },
      });
    }
  }
  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger asChild>{children}</DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>{wallet ? t('Edit Wallet') : t('Add Wallet')}</DialogTitle>
          <DialogDescription hidden></DialogDescription>
        </DialogHeader>

        <form className="space-y-4" onSubmit={handleSubmit}>
          <TranslatableInput
            required
            label={t('Name')}
            values={data.name}
            onChange={(locale, value) => setData(`name`, { ...data.name, [locale]: value })}
            errors={{ errors, name: 'name' }}
          />

          <TranslatableInput
            label={t('Description')}
            values={data.description}
            onChange={(locale, value) => setData(`description`, { ...data.description, [locale]: value })}
            errors={{ errors, name: 'description' }}
          />

          {!wallet && (
            <CurrencyField
              required
              label={t('Initial Amount')}
              value={data.balance}
              onChange={(value) => setData('balance', value)}
              error={errors.balance}
            />
          )}

          <div className="flex justify-end">
            <SubmitButton isSubmitting={processing}>{t('Save')}</SubmitButton>
          </div>
        </form>
      </DialogContent>
    </Dialog>
  );
}
