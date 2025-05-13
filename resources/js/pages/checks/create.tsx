import { Form } from '@/components/forms/Form';
import { ComboboxField } from '@/components/forms/inputs/ComboboxField';
import { CurrencyField } from '@/components/forms/inputs/CurrencyField';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';

import type { SelectOption } from '@/types';
import { useForm } from '@inertiajs/react';
import { formatDate } from 'date-fns';
import { useLaravelReactI18n } from 'laravel-react-i18n';

interface CreatePageProps {
  walletOptions: SelectOption[];
  memberOptions: SelectOption[];
  checkTypesOptions: SelectOption[];
  expenseTypesOptions: SelectOption[];
}

type CreateForm = {
  wallet_id: string;
  member_id: string;
  amount: string;
  date: string;
  type: string;
  note: string;
  expense_type_id: string;
};

export default function ChecksCreate({ walletOptions, memberOptions, checkTypesOptions, expenseTypesOptions }: CreatePageProps) {
  const { t } = useLaravelReactI18n();
  const { data, setData, post, errors, processing } = useForm<CreateForm>({
    wallet_id: walletOptions[0]?.value.toString() ?? '',
    member_id: memberOptions[0]?.value.toString() ?? '',
    amount: '',
    date: formatDate(new Date(), 'yyyy-MM-dd'),
    type: checkTypesOptions[0]?.value.toString() ?? '',
    note: '',
    expense_type_id: expenseTypesOptions[0]?.value.toString() ?? '',
  });

  function handleSubmit() {
    post(route('checks.store'));
  }

  return (
    <AppLayout title={t('Create :model',{model:t('Check')})} breadcrumbs={[{ title: t('Checks'), href: route('checks.index') }, { title: t('Create :model',{model:t('Check')}) }]}>
      <PageTitle>{t('Create :model',{model:t('Check')})}</PageTitle>

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
              label={t('Expense Type')}
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
