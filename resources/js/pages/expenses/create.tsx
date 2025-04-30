import { Form } from '@/components/forms/Form';
import { CurrencyField } from '@/components/forms/inputs/CurrencyField';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { useCurrency } from '@/hooks/use-currency';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, type SelectOption } from '@/types';
import { useForm } from '@inertiajs/react';
import { formatDate } from 'date-fns';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { TrashIcon } from 'lucide-react';

interface CreatePageProps {
  wallets: SelectOption[];
  members: SelectOption[];
  expenseTypes: SelectOption[];
}

interface CreateForm {
  expenses: {
    expense_type_id: string;
    wallet_id: string;
    member_id: string;
    amount: string;
    note: string;
    date: string;
  }[];
}
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Expenses',
    href: route('expenses.index'),
  },
  {
    title: 'New Expense',
  },
];

export default function Create({ wallets, members, expenseTypes }: CreatePageProps) {
  const { t } = useLaravelReactI18n();
  const { formatCurrency } = useCurrency();
  const initialExpense: CreateForm['expenses'][number] = {
    wallet_id: wallets[0]?.value.toString() ?? '',
    expense_type_id: expenseTypes[0]?.value.toString() ?? '',
    amount: '',
    note: '',
    member_id: '',
    date: formatDate(new Date(), 'yyyy-MM-dd'),
  };

  const { data, setData, post, errors, processing } = useForm<Required<CreateForm>>({
    expenses: [initialExpense],
  });
  const totalExpenses = data.expenses.reduce((acc, expense) => acc + parseFloat(expense.amount || '0'), 0);

  function handleSubmit() {
    post(route('expenses.store'));
  }

  function handleAddExpense() {
    setData('expenses', [...data.expenses, initialExpense]);
  }

  function handleRemoveExpense(index: number) {
    const updatedExpenses = [...data.expenses];
    updatedExpenses.splice(index, 1);
    setData('expenses', updatedExpenses);
  }

  function handleUpdateExpense(index: number, field: string, value: unknown) {
    const updatedExpenses = [...data.expenses];
    if (updatedExpenses[index] === undefined) {
      return;
    }
    updatedExpenses[index] = {
      ...updatedExpenses[index],
      [field]: value,
    };
    setData('expenses', updatedExpenses);
  }

  const walletExpenses = data.expenses.reduce((acc: Record<string, number>, expense) => {
    const walletName = wallets.find((wallet) => wallet.value.toString() === expense.wallet_id)?.label;
    if (!walletName) {
      return acc;
    }
    return {
      ...acc,
      [walletName]: (acc[walletName] || 0) + parseFloat(expense.amount || '0'),
    };
  }, {});

  return (
    <AppLayout title={t('Expenses')} breadcrumbs={breadcrumbs}>
      <PageTitle>{t('New Expense')}</PageTitle>
      <div className="mt-2 flex items-center justify-center">
        <Form isSubmitting={processing} className="w-full max-w-2xl" onSubmit={handleSubmit}>
          <div className="space-y-4 py-2">
            {data.expenses.map((expense, index) => (
              <fieldset className="space-y-2" key={index}>
                {data.expenses.length > 1 && (
                  <legend className="px-2 text-sm font-semibold">
                    <Button size="icon" className="size-6" variant="destructive" type="button" onClick={() => handleRemoveExpense(index)}>
                      <TrashIcon className="size-4" />
                    </Button>
                  </legend>
                )}
                <FieldsGrid className="grow">
                  <DateField
                    required
                    label={t('Date of Expense')}
                    value={expense.date}
                    onChange={(value) => handleUpdateExpense(index, 'date', value)}
                    error={errors[`expenses.${index}.date` as keyof typeof data]}
                  />
                  <SelectField
                    required
                    label={t('Wallet')}
                    value={expense.wallet_id}
                    onChange={(value) => {
                      handleUpdateExpense(index, 'wallet_id', value);
                    }}
                    error={errors[`expenses.${index}.wallet_id` as keyof typeof data]}
                    options={wallets}
                  />
                </FieldsGrid>
                <FieldsGrid cols={3} className="grow">
                  <SelectField
                    label={t('Member')}
                    value={expense.member_id}
                    onChange={(value) => {
                      handleUpdateExpense(index, 'member_id', value);
                    }}
                    error={errors[`expenses.${index}.member_id` as keyof typeof data]}
                    options={members}
                  />
                  <SelectField
                    required
                    label={t('Expense type')}
                    value={expense.expense_type_id}
                    onChange={(value) => {
                      handleUpdateExpense(index, 'expense_type_id', value);
                    }}
                    error={errors[`expenses.${index}.expense_type_id` as keyof typeof data]}
                    options={expenseTypes}
                  />

                  <CurrencyField
                    required
                    label={t('Amount')}
                    value={expense.amount}
                    onChange={(value) => {
                      handleUpdateExpense(index, 'amount', value);
                    }}
                    error={errors[`expenses.${index}.amount` as keyof typeof data]}
                  />
                </FieldsGrid>
                <InputField
                  label={t('Note')}
                  value={expense.note}
                  onChange={(value) => {
                    handleUpdateExpense(index, 'note', value);
                  }}
                  error={errors[`expenses.${index}.note` as keyof typeof data]}
                />
              </fieldset>
            ))}
          </div>

          <Button size="sm" variant="secondary" type="button" onClick={handleAddExpense}>
            {t('Add expense')}
          </Button>
          {Object.keys(walletExpenses).length > 0 && (
            <>
              <p className="text-muted-foreground mt-2">
                {t('Expenses total')}: <span className="font-semibold">{formatCurrency(totalExpenses)}</span>
              </p>

              <div className="mt-2 space-y-1">
                {Object.entries(walletExpenses).map(([walletName, total]) => (
                  <p key={walletName} className="text-muted-foreground text-sm">
                    {walletName}: <span className="font-semibold">{formatCurrency(total)}</span>
                  </p>
                ))}
              </div>
            </>
          )}
        </Form>
      </div>
    </AppLayout>
  );
}
