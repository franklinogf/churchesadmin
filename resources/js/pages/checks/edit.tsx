import { Form } from '@/components/forms/Form';
import { ComboboxField } from '@/components/forms/inputs/ComboboxField';
import { CurrencyField } from '@/components/forms/inputs/CurrencyField';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { SwitchField } from '@/components/forms/inputs/SwitchField';
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
}

type EditForm = {
  wallet_id: string;
  member_id: string;
  amount: string;
  date: string;
  type: string;
  confirmed: boolean;
};

export default function ChecksEdit({ wallets, members, checkTypes, check }: EditPageProps) {
  const { toPositive } = useCurrency();
  const { data, setData, put, errors, processing } = useForm<EditForm>({
    wallet_id: check.transaction.wallet?.slug ?? '',
    member_id: check.member.id.toString(),
    amount: toPositive(check.transaction.amountFloat),
    date: formatDate(new Date(), 'yyyy-MM-dd'),
    type: check.type,
    confirmed: check.transaction.confirmed,
  });

  function handleSubmit() {
    put(route('checks.update', check.id));
  }

  return (
    <AppLayout title="Edit check" breadcrumbs={[{ title: 'Checks', href: route('checks.index') }, { title: 'Edit check' }]}>
      <PageTitle>Edit Check</PageTitle>

      <div className="mx-auto mt-4 w-full max-w-2xl">
        <Form onSubmit={handleSubmit} isSubmitting={processing}>
          <ComboboxField
            required
            value={data.member_id}
            label="Member"
            onChange={(value) => setData('member_id', value)}
            options={members}
            error={errors.member_id}
          />
          <FieldsGrid>
            <SelectField
              required
              label="Wallet"
              value={data.wallet_id}
              onChange={(value) => setData('wallet_id', value)}
              options={wallets}
              error={errors.wallet_id}
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

          <SwitchField
            label="Confirmed"
            description="It will be deducted from the wallet"
            value={data.confirmed}
            onChange={(value) => setData('confirmed', value)}
            error={errors.confirmed}
          />
        </Form>
      </div>
    </AppLayout>
  );
}
