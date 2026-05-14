import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';

import ExpenseController from '@/actions/App/Http/Controllers/ExpenseController';
import Datatable from '@/components/datatable/datatable';
import { PageTitle } from '@/components/PageTitle';
import type { Expense } from '@/types/models/expense';
import { Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { columns } from './includes/columns';

interface IndexPageProps {
  expenses: Expense[];
}

export default function Index({ expenses }: IndexPageProps) {
  const { t: tPages } = useTranslation('pages');
  return (
    <AppLayout title={tPages(($) => $.expenses.index.expenses)} breadcrumbs={[{ title: tPages(($) => $.expenses.index.expenses) }]}>
      <PageTitle>{tPages(($) => $.expenses.index.expenses)}</PageTitle>
      <Datatable
        renderLeftTop={
          <Button asChild>
            <Link href={ExpenseController.create()}>
              {tPages(($) => $.expenses.index.newModel, { model: tPages(($) => $.expenses.index.expense) })}
            </Link>
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
