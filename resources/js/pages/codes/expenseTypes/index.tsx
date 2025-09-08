import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { ExpenseTypeForm } from '@/components/forms/expense-type-form';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import type { ExpenseType } from '@/types/models/expense-type';
import { useState } from 'react';
import { columns } from './includes/columns';

export default function ExpenseTypesIndex({ expenseTypes }: { expenseTypes: ExpenseType[] }) {
  const { t } = useTranslations();
  const [open, setOpen] = useState(false);
  return (
    <AppLayout title={t('Expense types')} breadcrumbs={[{ title: t('Expense types') }]}>
      <PageTitle>{t('Expense types')}</PageTitle>
      <ExpenseTypeForm open={open} setOpen={setOpen} />
      <div className="mx-auto w-full max-w-xl">
        <DataTable
          headerButton={
            <Button size="sm" onClick={() => setOpen(true)}>
              {t('Add :model', { model: t('Expense type') })}
            </Button>
          }
          data={expenseTypes}
          columns={columns}
        />
      </div>
    </AppLayout>
  );
}
