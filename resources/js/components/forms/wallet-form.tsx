import { CurrencyField } from '@/components/forms/inputs/CurrencyField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import type { Wallet } from '@/types/models/wallet';
import { useForm } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';

type WalletForm = {
  name: string;
  description: string;
  balance: string;
  bank_name: string;
  bank_account_number: string;
  bank_routing_number: string;
};

export function WalletForm({ wallet, open, setOpen }: { wallet?: Wallet; open: boolean; setOpen: (open: boolean) => void }) {
  const { t } = useLaravelReactI18n();

  const { data, setData, post, put, errors, reset, processing } = useForm<WalletForm>({
    name: wallet?.name ?? '',
    description: wallet?.description ?? '',
    balance: wallet?.balanceFloat ?? '',
    bank_name: wallet?.bankName ?? '',
    bank_account_number: wallet?.bankAccountNumber ?? '',
    bank_routing_number: wallet?.bankRoutingNumber ?? '',
  });

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    if (wallet) {
      put(route('wallets.update', wallet.id), {
        onSuccess: () => {
          setOpen(false);
        },
      });
    } else {
      post(route('wallets.store'), {
        preserveState: false,
        onSuccess: () => {
          setOpen(false);
          reset();
        },
      });
    }
  }

  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={wallet ? t('Edit :model', { model: t('Wallet') }) : t('Add :model', { model: t('Wallet') })}
      description={wallet ? t('Edit the details of this :model', { model: t('Wallet') }) : t('Create a new :model?', { model: t('Wallet') })}
    >
      <form className="space-y-4" onSubmit={handleSubmit}>
        <InputField required label={t('Name')} value={data.name} onChange={(value) => setData(`name`, value)} error={errors.name} />

        <InputField
          label={t('Description')}
          value={data.description}
          onChange={(value) => setData(`description`, value)}
          error={errors.description}
        />

        {(!wallet || wallet?.transactionsCount === 0) && (
          <CurrencyField label={t('Initial Amount')} value={data.balance} onChange={(value) => setData('balance', value)} error={errors.balance} />
        )}

        <InputField
          required
          label={t('Bank Name')}
          value={data.bank_name}
          onChange={(value) => setData('bank_name', value)}
          error={errors.bank_name}
        />
        <FieldsGrid className="grid-cols-2">
          <InputField
            required
            label={t('Routing Number')}
            value={data.bank_routing_number}
            onChange={(value) => setData('bank_routing_number', value)}
            error={errors.bank_routing_number}
          />

          <InputField
            required
            label={t('Account Number')}
            value={data.bank_account_number}
            onChange={(value) => setData('bank_account_number', value)}
            error={errors.bank_account_number}
          />
        </FieldsGrid>

        <ResponsiveModalFooterSubmit isSubmitting={processing} label={t('Save')} />
      </form>
    </ResponsiveModal>
  );
}
