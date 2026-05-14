import WalletController from '@/actions/App/Http/Controllers/WalletController';
import { CurrencyField } from '@/components/forms/inputs/CurrencyField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import type { Wallet } from '@/types/models/wallet';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
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
  const { t: tCommon } = useTranslation('common');
  //   const { data, setData, submit, errors, reset, processing } = useForm<WalletForm>({
  //     name: wallet?.name ?? '',
  //     description: wallet?.description ?? '',
  //     balance: wallet?.balanceFloat ?? '',
  //     bank_name: wallet?.bankName ?? '',
  //     bank_account_number: wallet?.bankAccountNumber ?? '',
  //     bank_routing_number: wallet?.bankRoutingNumber ?? '',
  //   });

  const MODEL = tCommon(($) => $.components.forms.walletForm.wallet);
  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={
        wallet
          ? tCommon(($) => $.components.forms.walletForm.editModel, { model: MODEL })
          : tCommon(($) => $.components.forms.walletForm.addModel, { model: MODEL })
      }
      description={
        wallet
          ? tCommon(($) => $.components.forms.walletForm.editTheDetailsOfThisModel, { model: MODEL })
          : tCommon(($) => $.components.forms.walletForm.createANewModel, { model: MODEL })
      }
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
            <InputField
              required
              label={tCommon(($) => $.components.forms.walletForm.name)}
              name="name"
              error={errors.name}
              defaultValue={wallet?.name}
            />

            <InputField
              label={tCommon(($) => $.components.forms.walletForm.description)}
              name="description"
              error={errors.description}
              defaultValue={wallet?.description ?? ''}
            />

            {(!wallet || wallet?.transactionsCount === 0) && (
              <CurrencyField
                label={tCommon(($) => $.components.forms.walletForm.initialAmount)}
                name="balance"
                error={errors.balance}
                defaultValue={wallet?.balanceFloat ?? ''}
              />
            )}

            <InputField
              required
              label={tCommon(($) => $.components.forms.walletForm.bankName)}
              name="bank_name"
              error={errors.bank_name}
              defaultValue={wallet?.bankName ?? ''}
            />

            <FieldsGrid cols={2}>
              <InputField
                required
                label={tCommon(($) => $.components.forms.walletForm.routingNumber)}
                name="bank_routing_number"
                error={errors.bank_routing_number}
                defaultValue={wallet?.bankRoutingNumber ?? ''}
              />
              <InputField
                required
                label={tCommon(($) => $.components.forms.walletForm.accountNumber)}
                name="bank_account_number"
                error={errors.bank_account_number}
                defaultValue={wallet?.bankAccountNumber ?? ''}
              />
            </FieldsGrid>

            <ResponsiveModalFooterSubmit isSubmitting={processing} label={tCommon(($) => $.components.forms.walletForm.save)} />
          </FieldGroup>
        )}
      </Form>
    </ResponsiveModal>
  );
}
