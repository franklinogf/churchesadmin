import ExpenseTypeController from '@/actions/App/Http/Controllers/ExpenseTypeController';
import { InputField } from '@/components/forms/inputs/InputField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import type { ExpenseType } from '@/types/models/expense-type';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { FieldGroup } from '../ui/field';
import { CurrencyField } from './inputs/CurrencyField';

export function ExpenseTypeForm({ expenseType, open, setOpen }: { expenseType?: ExpenseType; open: boolean; setOpen: (open: boolean) => void }) {
  const { t: tCommon } = useTranslation('common');
  const MODEL = tCommon(($) => $.components.forms.expenseTypeForm.expenseType);
  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={
        expenseType
          ? tCommon(($) => $.components.forms.expenseTypeForm.editModel, { model: MODEL })
          : tCommon(($) => $.components.forms.expenseTypeForm.addModel, { model: MODEL })
      }
      description={
        expenseType
          ? tCommon(($) => $.components.forms.expenseTypeForm.editTheDetailsOfThisModel, { model: MODEL })
          : tCommon(($) => $.components.forms.expenseTypeForm.createANewModel, { model: MODEL })
      }
    >
      <Form
        disableWhileProcessing
        action={expenseType ? ExpenseTypeController.update(expenseType.id) : ExpenseTypeController.store()}
        onSuccess={() => {
          setOpen(false);
        }}
        options={{ only: ['expenseTypes'] }}
      >
        {({ errors, processing }) => (
          <FieldGroup>
            <InputField
              required
              label={tCommon(($) => $.components.forms.expenseTypeForm.name)}
              name="name"
              defaultValue={expenseType?.name}
              error={errors.name}
            />
            <CurrencyField
              label={tCommon(($) => $.components.forms.expenseTypeForm.defaultAmount)}
              name="default_amount"
              defaultValue={expenseType?.defaultAmount?.toString()}
              error={errors.default_amount}
            />

            <ResponsiveModalFooterSubmit isSubmitting={processing} label={tCommon(($) => $.components.forms.expenseTypeForm.save)} />
          </FieldGroup>
        )}
      </Form>
    </ResponsiveModal>
  );
}
