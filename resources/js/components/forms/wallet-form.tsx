import WalletController from '@/actions/App/Http/Controllers/WalletController';
import { CurrencyField } from '@/components/forms/inputs/CurrencyField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import { useTranslations } from '@/hooks/use-translations';
import type { Wallet } from '@/types/models/wallet';
import { Form } from '@inertiajs/react';
import { FieldGroup } from '../ui/field';

type WalletForm = {
  name: string;
  description: string;
  balance: string;
  bank_name: string;
  bank_account_number: string;
  bank_routing_number: string;
};

export function WalletForm({ wallet, open, setOpen }: { wallet?: Wallet; open: boolean; setOpen: (open: boolean) => void }) {
  const { t } = useTranslations();

  //   const { data, setData, submit, errors, reset, processing } = useForm<WalletForm>({
  //     name: wallet?.name ?? '',
  //     description: wallet?.description ?? '',
  //     balance: wallet?.balanceFloat ?? '',
  //     bank_name: wallet?.bankName ?? '',
  //     bank_account_number: wallet?.bankAccountNumber ?? '',
  //     bank_routing_number: wallet?.bankRoutingNumber ?? '',
  //   });

  const MODEL = t('Wallet');
  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={wallet ? t('Edit :model', { model: MODEL }) : t('Add :model', { model: MODEL })}
      description={wallet ? t('Edit the details of this :model', { model: MODEL }) : t('Create a new :model', { model: MODEL })}
    >
      <Form
        disableWhileProcessing
        action={wallet ? WalletController.update(wallet.id) : WalletController.store()}
        onSuccess={() => {
          setOpen(false);
        }}
      >
        {({ errors, processing }) => (
          <FieldGroup>
            <InputField required label={t('Name')} name="name" error={errors.name} defaultValue={wallet?.name} />

            <InputField label={t('Description')} name="description" error={errors.description} defaultValue={wallet?.description ?? ''} />

            {(!wallet || wallet?.transactionsCount === 0) && (
              <CurrencyField label={t('Initial Amount')} name="balance" error={errors.balance} defaultValue={wallet?.balanceFloat ?? ''} />
            )}

            <InputField required label={t('Bank Name')} name="bank_name" error={errors.bank_name} defaultValue={wallet?.bankName ?? ''} />

            <FieldsGrid cols={2}>
              <InputField
                required
                label={t('Routing Number')}
                name="bank_routing_number"
                error={errors.bank_routing_number}
                defaultValue={wallet?.bankRoutingNumber ?? ''}
              />
              <InputField
                required
                label={t('Account Number')}
                name="bank_account_number"
                error={errors.bank_account_number}
                defaultValue={wallet?.bankAccountNumber ?? ''}
              />
            </FieldsGrid>

            <ResponsiveModalFooterSubmit isSubmitting={processing} label={t('Save')} />
          </FieldGroup>
        )}
      </Form>
    </ResponsiveModal>
  );
}
