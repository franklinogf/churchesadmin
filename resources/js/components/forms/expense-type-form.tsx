import { InputField } from '@/components/forms/inputs/InputField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import type { ExpenseType } from '@/types/models/expense-type';
import { useForm } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';

export function ExpenseTypeForm({ expenseType, open, setOpen }: { expenseType?: ExpenseType; open: boolean; setOpen: (open: boolean) => void }) {
  const { t } = useLaravelReactI18n();
  const { data, setData, post, put, errors, processing } = useForm({
    name: expenseType?.name ?? '',
  });

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    if (expenseType) {
      put(route('codes.expenseTypes.update', expenseType.id), {
        onSuccess: () => {
          setOpen(false);
        },
      });
    } else {
      post(route('codes.expenseTypes.store'), {
        preserveState: true,
        onSuccess: () => {
          setOpen(false);
          setData({ name: '' });
        },
      });
    }
  }
  return (
    <ResponsiveModal open={open} setOpen={setOpen} title={expenseType ? t('Edit Expense Type') : t('Add Expense Type')}>
      <form className="space-y-4" onSubmit={handleSubmit}>
        <InputField required label={t('Name')} value={data.name} onChange={(value) => setData('name', value)} error={errors.name} />

        <ResponsiveModalFooterSubmit isSubmitting={processing} label={t('Save')} />
      </form>
    </ResponsiveModal>
  );
}
