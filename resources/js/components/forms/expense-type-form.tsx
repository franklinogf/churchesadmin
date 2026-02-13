import ExpenseTypeController from '@/actions/App/Http/Controllers/ExpenseTypeController';
import { InputField } from '@/components/forms/inputs/InputField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import { useTranslations } from '@/hooks/use-translations';
import type { ExpenseType } from '@/types/models/expense-type';
import { Form } from '@inertiajs/react';
import { FieldGroup } from '../ui/field';
import { CurrencyField } from './inputs/CurrencyField';

export function ExpenseTypeForm({ expenseType, open, setOpen }: { expenseType?: ExpenseType; open: boolean; setOpen: (open: boolean) => void }) {
  const { t } = useTranslations();

  const MODEL = t('Expense type');
  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={expenseType ? t('Edit :model', { model: MODEL }) : t('Add :model', { model: MODEL })}
      description={expenseType ? t('Edit the details of this :model', { model: MODEL }) : t('Create a new :model', { model: MODEL })}
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
            <InputField required label={t('Name')} name="name" defaultValue={expenseType?.name} error={errors.name} />
            <CurrencyField
              label={t('Default amount')}
              name="default_amount"
              defaultValue={expenseType?.defaultAmount?.toString()}
              error={errors.default_amount}
            />

            <ResponsiveModalFooterSubmit isSubmitting={processing} label={t('Save')} />
          </FieldGroup>
        )}
      </Form>
    </ResponsiveModal>
  );
}
