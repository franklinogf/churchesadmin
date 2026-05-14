import Datatable from '@/components/datatable/datatable';
import { ExpenseTypeForm } from '@/components/forms/expense-type-form';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import type { ExpenseType } from '@/types/models/expense-type';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { columns } from './includes/columns';

export default function ExpenseTypesIndex({ expenseTypes }: { expenseTypes: ExpenseType[] }) {
  const { t: tPages } = useTranslation('pages');
  const [open, setOpen] = useState(false);
  return (
    <AppLayout
      title={tPages(($) => $.codes.expenseTypes.index.expenseTypes)}
      breadcrumbs={[{ title: tPages(($) => $.codes.expenseTypes.index.expenseTypes) }]}
    >
      <PageTitle>{tPages(($) => $.codes.expenseTypes.index.expenseTypes)}</PageTitle>
      <ExpenseTypeForm open={open} setOpen={setOpen} />
      <div className="mx-auto w-full max-w-xl">
        <Datatable
          renderLeftTop={
            <Button size="sm" onClick={() => setOpen(true)}>
              {tPages(($) => $.codes.expenseTypes.index.addModel, { model: tPages(($) => $.codes.expenseTypes.index.expenseType) })}
            </Button>
          }
          data={expenseTypes}
          columns={columns}
        />
      </div>
    </AppLayout>
  );
}
