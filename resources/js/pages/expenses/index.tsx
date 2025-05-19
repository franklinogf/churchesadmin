import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';

import { PageTitle } from '@/components/PageTitle';
import type { Expense } from '@/types/models/expense';
import { Link } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { columns } from './includes/columns';

interface IndexPageProps {
  expenses: Expense[];
}

export default function Index({ expenses }: IndexPageProps) {
  const { t } = useLaravelReactI18n();
  return (
    <AppLayout title={t('Expenses')} breadcrumbs={[{ title: t('Expenses') }]}>
      <PageTitle>{t('Expenses')}</PageTitle>
      <DataTable
        headerButton={
          <Button asChild>
            <Link href={route('expenses.create')}>{t('New :model', { model: t('Expense') })}</Link>
          </Button>
        }
        data={expenses}
        columns={columns}
        sortingState={[{ id: 'date', desc: true }]}
        visibilityState={{ confirmed: false }}
      />
    </AppLayout>
  );
}
