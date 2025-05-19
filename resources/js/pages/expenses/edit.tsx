import { Form } from '@/components/forms/Form';
import { CurrencyField } from '@/components/forms/inputs/CurrencyField';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import { useCurrency } from '@/hooks/use-currency';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, type SelectOption } from '@/types';
import type { Expense } from '@/types/models/expense';
import type { Wallet } from '@/types/models/wallet';
import { useForm } from '@inertiajs/react';
import { formatDate, parseISO } from 'date-fns';
import { useLaravelReactI18n } from 'laravel-react-i18n';

interface CreatePageProps {
  expense: Expense;
  wallets: Wallet[];
  memberOptions: SelectOption[];
  expenseTypesOptions: SelectOption[];
  walletOptions: SelectOption[];
}

interface CreateForm {
  expense_type_id: string;
  wallet_id: string;
  member_id: string;
  amount: string;
  note: string;
  date: string;
}

export default function Create({ wallets, memberOptions, expenseTypesOptions, walletOptions, expense }: CreatePageProps) {
  const { t } = useLaravelReactI18n();
  const { formatCurrency, toPositive } = useCurrency();

  const { data, setData, put, errors, processing } = useForm<Required<CreateForm>>({
    wallet_id: expense.transaction.wallet?.id.toString() || '',
    expense_type_id: expense.expenseType.id.toString(),
    member_id: expense.member?.id.toString() || '',
    amount: toPositive(expense.transaction.amountFloat),
    note: expense.note || '',
    date: formatDate(parseISO(expense.date), 'yyyy-MM-dd'),
  });

  function handleSubmit() {
    put(route('expenses.update', expense.id), {
      preserveScroll: true,
    });
  }
  const wallet = wallets.find((wallet) => wallet.id.toString() === data.wallet_id);

  const breadcrumbs: BreadcrumbItem[] = [
    {
      title: t('Expenses'),
      href: route('expenses.index'),
    },
    {
      title: t('Edit :model', { model: t('Expense') }),
    },
  ];

  return (
    <AppLayout title={t('Expenses')} breadcrumbs={breadcrumbs}>
      <PageTitle>{t('Edit :model', { model: t('Expense') })}</PageTitle>
      <div className="mt-2 flex items-center justify-center">
        <Form isSubmitting={processing} className="w-full max-w-2xl" onSubmit={handleSubmit}>
          <div className="space-y-4 py-2">
            <FieldsGrid className="grow">
              <DateField required label={t('Date of Expense')} value={data.date} onChange={(value) => setData('date', value)} error={errors.date} />
              <div className="flex flex-col">
                <SelectField
                  required
                  label={t('Wallet')}
                  value={data.wallet_id}
                  onChange={(value) => setData('wallet_id', value)}
                  error={errors.wallet_id}
                  options={walletOptions}
                />
                {wallet && (
                  <p className="text-muted-foreground flex justify-end gap-0.5 text-xs">
                    {t('Current balance')}: <span className="font-semibold">{formatCurrency(wallet.balanceFloat)}</span>
                    <span>+</span>
                    <span className="font-semibold">{formatCurrency(toPositive(expense.transaction.amountFloat))}</span>
                  </p>
                )}
              </div>
            </FieldsGrid>
            <FieldsGrid cols={3} className="grow">
              <SelectField
                label={t('Member')}
                value={data.member_id}
                onChange={(value) => setData('member_id', value)}
                error={errors.member_id}
                options={memberOptions}
              />
              <SelectField
                required
                label={t('Expense type')}
                value={data.expense_type_id}
                onChange={(value) => setData('expense_type_id', value)}
                error={errors.expense_type_id}
                options={expenseTypesOptions}
              />

              <CurrencyField required label={t('Amount')} value={data.amount} onChange={(value) => setData('amount', value)} error={errors.amount} />
            </FieldsGrid>
            <InputField label={t('Note')} value={data.note} onChange={(value) => setData('note', value)} error={errors.note} />
          </div>
        </Form>
      </div>
    </AppLayout>
  );
}
