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

interface CreatePageProps {
  wallets: SelectOption[];
  members: SelectOption[];
  checkTypes: SelectOption[];
  expenseTypes: SelectOption[];
}

type CreateForm = {
  wallet_slug: string;
  member_id: string;
  amount: string;
  date: string;
  type: string;
  note: string;
  expense_type_id: string;
};

export default function ChecksCreate({ wallets, members, checkTypes, expenseTypes }: CreatePageProps) {
  const { data, setData, post, errors, processing } = useForm<CreateForm>({
    wallet_slug: wallets[0]?.value.toString() ?? '',
    member_id: members[0]?.value.toString() ?? '',
    amount: '',
    date: formatDate(new Date(), 'yyyy-MM-dd'),
    type: checkTypes[0]?.value.toString() ?? '',
    note: '',
    expense_type_id: expenseTypes[0]?.value.toString() ?? '',
  });

  function handleSubmit() {
    post(route('checks.store'));
  }

  return (
    <AppLayout title="Create check" breadcrumbs={[{ title: 'Checks', href: route('checks.index') }, { title: 'Create check' }]}>
      <PageTitle>Create Check</PageTitle>

      <div className="mx-auto mt-4 w-full max-w-2xl">
        <Form onSubmit={handleSubmit} isSubmitting={processing}>
          <FieldsGrid>
            <ComboboxField
              required
              value={data.member_id}
              label="Member"
              onChange={(value) => setData('member_id', value)}
              options={members}
              error={errors.member_id}
            />
            <ComboboxField
              required
              value={data.expense_type_id}
              label="Expense Type"
              onChange={(value) => setData('expense_type_id', value)}
              options={expenseTypes}
              error={errors.expense_type_id}
            />
          </FieldsGrid>
          <FieldsGrid>
            <SelectField
              required
              label="Wallet"
              value={data.wallet_slug}
              onChange={(value) => setData('wallet_slug', value)}
              options={wallets}
              error={errors.wallet_slug}
            />
            <SelectField
              required
              label="Type"
              value={data.type}
              onChange={(value) => setData('type', value)}
              options={checkTypes}
              error={errors.type}
            />
          </FieldsGrid>
          <CurrencyField label="Amount" required value={data.amount} onChange={(value) => setData('amount', value)} error={errors.amount} />

          <DateField required label="Date" value={data.date} onChange={(value) => setData('date', value)} error={errors.date} />

          <InputField label="Note" value={data.note} onChange={(value) => setData('note', value)} error={errors.note} />
        </Form>
      </div>
    </AppLayout>
  );
}
