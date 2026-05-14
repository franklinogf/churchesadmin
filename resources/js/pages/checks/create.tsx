import { Form } from '@/components/forms/Form';
import { ComboboxField } from '@/components/forms/inputs/ComboboxField';
import { CurrencyField } from '@/components/forms/inputs/CurrencyField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';

import CheckController from '@/actions/App/Http/Controllers/CheckController';
import { DateField } from '@/components/forms/inputs/DateField';
import type { SelectOption } from '@/types';
import { useForm } from '@inertiajs/react';
import { formatDate } from 'date-fns';
import { useTranslation } from 'react-i18next';

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
  const { t: tPages } = useTranslation('pages');
  const { data, setData, submit, errors, processing } = useForm<CreateForm>({
    wallet_id: walletOptions[0]?.value.toString() ?? '',
    member_id: memberOptions[0]?.value.toString() ?? '',
    amount: '',
    date: formatDate(new Date(), 'yyyy-MM-dd'),
    type: checkTypesOptions[0]?.value.toString() ?? '',
    note: '',
    expense_type_id: expenseTypesOptions[0]?.value.toString() ?? '',
  });

  function handleSubmit() {
    submit(CheckController.store());
  }

  return (
    <AppLayout
      title={tPages(($) => $.checks.create.createModel, { model: tPages(($) => $.checks.create.check) })}
      breadcrumbs={[
        { title: tPages(($) => $.checks.create.checks), href: CheckController.index().url },
        { title: tPages(($) => $.checks.create.createModel, { model: tPages(($) => $.checks.create.check) }) },
      ]}
    >
      <PageTitle>{tPages(($) => $.checks.create.createModel, { model: tPages(($) => $.checks.create.check) })}</PageTitle>

      <div className="mx-auto mt-4 w-full max-w-2xl">
        <Form onSubmit={handleSubmit} isSubmitting={processing}>
          <FieldsGrid>
            <ComboboxField
              required
              value={data.member_id}
              label={tPages(($) => $.checks.create.member)}
              onChange={(value) => setData('member_id', value)}
              options={memberOptions}
              error={errors.member_id}
            />
            <ComboboxField
              required
              value={data.expense_type_id}
              label={tPages(($) => $.checks.create.expenseType)}
              onChange={(value) => setData('expense_type_id', value)}
              options={expenseTypesOptions}
              error={errors.expense_type_id}
            />
          </FieldsGrid>
          <FieldsGrid>
            <SelectField
              required
              label={tPages(($) => $.checks.create.wallet)}
              value={data.wallet_id}
              onValueChange={(value) => setData('wallet_id', value)}
              options={walletOptions}
              error={errors.wallet_id}
            />
            <SelectField
              required
              label={tPages(($) => $.checks.create.type)}
              value={data.type}
              onValueChange={(value) => setData('type', value)}
              options={checkTypesOptions}
              error={errors.type}
            />
          </FieldsGrid>
          <CurrencyField
            label={tPages(($) => $.checks.create.amount)}
            required
            value={data.amount}
            onValueChange={(value) => value !== undefined && setData('amount', value)}
            error={errors.amount}
          />

          <DateField
            required
            label={tPages(($) => $.checks.create.date)}
            value={data.date}
            onChange={(value) => value && setData('date', value)}
            error={errors.date}
          />

          <InputField
            label={tPages(($) => $.checks.create.note)}
            value={data.note}
            onChange={(value) => setData('note', value)}
            error={errors.note}
          />
        </Form>
      </div>
    </AppLayout>
  );
}
