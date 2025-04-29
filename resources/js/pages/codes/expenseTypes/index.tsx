import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import type { ExpenseType } from '@/types/models/expense-type';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { ExpenseTypeForm } from './components/ExpenseTypeForm';
import { columns } from './includes/columns';

export default function ExpenseTypesIndex({ expenseTypes }: { expenseTypes: ExpenseType[] }) {
  const { t } = useLaravelReactI18n();
  return (
    <AppLayout title={t('Expense Types')} breadcrumbs={[{ title: t('Expense Types') }]}>
      <PageTitle>{t('Expense Types')}</PageTitle>
      <DataTable
        headerButton={
          <ExpenseTypeForm>
            <Button>{t('Add Expense Type')}</Button>
          </ExpenseTypeForm>
        }
        data={expenseTypes}
        columns={columns}
      />
    </AppLayout>
  );
}
