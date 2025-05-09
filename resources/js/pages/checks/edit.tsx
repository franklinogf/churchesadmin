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

interface EditPageProps {
  wallets: SelectOption[];
  members: SelectOption[];
  checkTypes: SelectOption[];
  check: Check;
  expenseTypes: SelectOption[];
}

type EditForm = {
  wallet_slug: string;
  member_id: string;
  amount: string;
  date: string;
  type: string;
  note: string;
  expense_type_id: string;
};

export default function ChecksEdit({ wallets, members, checkTypes, check, expenseTypes }: EditPageProps) {
  const { toPositive } = useCurrency();
  const { data, setData, put, errors, processing } = useForm<EditForm>({
    wallet_slug: check.transaction.wallet?.slug ?? '',
    member_id: check.member.id.toString(),
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
    <AppLayout title="Edit check" breadcrumbs={[{ title: 'Checks', href: route('checks.index') }, { title: 'Edit check' }]}>
      <PageTitle>Edit Check</PageTitle>

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
