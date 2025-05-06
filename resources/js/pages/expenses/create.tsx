import { Form } from '@/components/forms/Form';
import { CurrencyField } from '@/components/forms/inputs/CurrencyField';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableFooter, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useCurrency } from '@/hooks/use-currency';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, type SelectOption } from '@/types';
import type { ExpenseType } from '@/types/models/expense-type';
import type { Wallet } from '@/types/models/wallet';
import { useForm } from '@inertiajs/react';
import { formatDate } from 'date-fns';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { TrashIcon } from 'lucide-react';
import { useMemo } from 'react';

interface CreatePageProps {
  wallets: Wallet[];
  members: SelectOption[];
  expenseTypes: ExpenseType[];
  expenseTypesOptions: SelectOption[];
  walletOptions: SelectOption[];
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

export default function Create({ wallets, members, expenseTypes, expenseTypesOptions, walletOptions }: CreatePageProps) {
  const { t } = useLaravelReactI18n();
  const { formatCurrency } = useCurrency();

  const initialExpense: CreateForm['expenses'][number] = {
    wallet_id: walletOptions[0]?.value.toString() ?? '',
    expense_type_id: '',
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
    post(route('expenses.store'), {
      preserveScroll: true,
    });
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

  const walletExpenses = useMemo(
    () =>
      data.expenses.reduce((acc: Record<string, { amount: number; total: string }>, expense) => {
        const wallet = wallets.find((wallet) => wallet.id.toString() === expense.wallet_id);
        if (!wallet) {
          return acc;
        }
        return {
          ...acc,
          [wallet.name]: {
            amount: (acc[wallet.name]?.amount || 0) + parseFloat(expense.amount || '0'),
            total: wallet.balanceFloat,
          },
        };
      }, {}),
    [data.expenses, wallets],
  );

  const breadcrumbs: BreadcrumbItem[] = [
    {
      title: t('Expenses'),
      href: route('expenses.index'),
    },
    {
      title: t('New Expense'),
    },
  ];

  return (
    <AppLayout title={t('Expenses')} breadcrumbs={breadcrumbs}>
      <PageTitle>{t('New Expense')}</PageTitle>
      <div className="mt-2 flex items-center justify-center">
        <Form isSubmitting={processing} className="w-full max-w-2xl" onSubmit={handleSubmit}>
          <div className="space-y-4 py-2">
            {data.expenses.map((expense, index) => {
              const wallet = wallets.find((wallet) => wallet.id.toString() === expense.wallet_id);

              return (
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
                    <div className="flex flex-col">
                      <SelectField
                        required
                        label={t('Wallet')}
                        value={expense.wallet_id}
                        onChange={(value) => {
                          handleUpdateExpense(index, 'wallet_id', value);
                        }}
                        error={errors[`expenses.${index}.wallet_id` as keyof typeof data]}
                        options={walletOptions}
                      />
                      {wallet && (
                        <p className="text-muted-foreground flex justify-end text-xs">
                          {t('Current balance')}: <span className="font-semibold">{formatCurrency(wallet.balanceFloat)}</span>
                        </p>
                      )}
                    </div>
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
                        const expenseType = expenseTypes.find((type) => type.id.toString() === value);
                        if (!expenseType) {
                          return;
                        }
                        handleUpdateExpense(index, 'expense_type_id', value);

                        handleUpdateExpense(index, 'amount', expenseType.defaultAmount !== null ? expenseType.defaultAmount.toString() : '');
                      }}
                      error={errors[`expenses.${index}.expense_type_id` as keyof typeof data]}
                      options={expenseTypesOptions}
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
              );
            })}
          </div>

          <Button size="sm" variant="secondary" type="button" onClick={handleAddExpense}>
            {t('Add expense')}
          </Button>
          <section className="mt-4">
            {Object.keys(walletExpenses).length > 0 && (
              <Table className="mx-auto max-w-lg">
                <TableCaption>{t('Summary')}</TableCaption>
                <TableHeader>
                  <TableRow>
                    <TableHead>{t('Wallet')}</TableHead>
                    <TableHead className="w-[100px] text-right">{t('Available funds')}</TableHead>
                    <TableHead className="text-right">{t('Expenses')}</TableHead>
                  </TableRow>
                </TableHeader>

                <TableBody>
                  {Object.entries(walletExpenses).map(([walletName, { amount, total }]) => (
                    <TableRow key={walletName}>
                      <TableCell>{walletName}</TableCell>
                      <TableCell className="text-right">{formatCurrency(total)}</TableCell>
                      <TableCell className="text-right">{formatCurrency(amount)}</TableCell>
                    </TableRow>
                  ))}
                </TableBody>

                <TableFooter>
                  <TableRow>
                    <TableCell colSpan={2}>{t('Expenses total')}</TableCell>
                    <TableCell className="text-right">{formatCurrency(totalExpenses)}</TableCell>
                  </TableRow>
                </TableFooter>
              </Table>
            )}
          </section>
        </Form>
      </div>
    </AppLayout>
  );
}
