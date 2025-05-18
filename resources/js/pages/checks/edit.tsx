import { Form } from '@/components/forms/Form';
import { ComboboxField } from '@/components/forms/inputs/ComboboxField';
import { CurrencyField } from '@/components/forms/inputs/CurrencyField';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import { useCurrency } from '@/hooks/use-currency';
import AppLayout from '@/layouts/app-layout';
import type { SelectOption } from '@/types';
import type { Check } from '@/types/models/check';
import { useForm } from '@inertiajs/react';
import { formatDate } from 'date-fns';
import { useLaravelReactI18n } from 'laravel-react-i18n';

interface EditPageProps {
  walletOptions: SelectOption[];
  memberOptions: SelectOption[];
  checkTypesOptions: SelectOption[];
  expenseTypesOptions: SelectOption[];
  check: Check;
}

type EditForm = {
  wallet_id: string;
  member_id: string;
  amount: string;
  date: string;
  type: string;
  note: string;
  expense_type_id: string;
};

export default function ChecksEdit({ walletOptions, memberOptions, checkTypesOptions, expenseTypesOptions, check }: EditPageProps) {
  const { toPositive } = useCurrency();
  const { t } = useLaravelReactI18n();
  const { data, setData, put, errors, processing } = useForm<EditForm>({
    wallet_id: walletOptions[0]?.value.toString() ?? '',
    member_id: memberOptions[0]?.value.toString() ?? '',
    amount: toPositive(check.transaction.amountFloat),
    date: formatDate(new Date(), 'yyyy-MM-dd'),
    type: check.type,
    note: check.note ?? '',
    expense_type_id: check.expenseType.id.toString(),
  });

  function handleSubmit() {
    put(route('checks.update', check.id));
  }

  return (
    <AppLayout
      title={t('Edit :model', { model: t('Check') })}
      breadcrumbs={[{ title: t('Checks'), href: route('checks.index') }, { title: t('Edit :model', { model: t('Check') }) }]}
    >
      <PageTitle>{t('Edit :model', { model: t('Check') })}</PageTitle>

      <div className="mx-auto mt-4 w-full max-w-2xl">
        <Form onSubmit={handleSubmit} isSubmitting={processing}>
          <FieldsGrid>
            <ComboboxField
              required
              value={data.member_id}
              label={t('Member')}
              onChange={(value) => setData('member_id', value)}
              options={memberOptions}
              error={errors.member_id}
            />
            <ComboboxField
              required
              value={data.expense_type_id}
              label={t('Expense type')}
              onChange={(value) => setData('expense_type_id', value)}
              options={expenseTypesOptions}
              error={errors.expense_type_id}
            />
          </FieldsGrid>
          <FieldsGrid>
            <SelectField
              required
              label={t('Wallet')}
              value={data.wallet_id}
              onChange={(value) => setData('wallet_id', value)}
              options={walletOptions}
              error={errors.wallet_id}
            />
            <SelectField
              required
              label={t('Type')}
              value={data.type}
              onChange={(value) => setData('type', value)}
              options={checkTypesOptions}
              error={errors.type}
            />
          </FieldsGrid>
          <CurrencyField label={t('Amount')} required value={data.amount} onChange={(value) => setData('amount', value)} error={errors.amount} />

          <DateField required label={t('Date')} value={data.date} onChange={(value) => setData('date', value)} error={errors.date} />

          <InputField label={t('Note')} value={data.note} onChange={(value) => setData('note', value)} error={errors.note} />
        </Form>
      </div>
    </AppLayout>
  );
}
