import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import type { Check } from '@/types/models/check';
import { Link } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { columns } from './includes/columns';

interface IndexPageProps {
  unconfirmedChecks: Check[];
}
export default function Index({ unconfirmedChecks }: IndexPageProps) {
  const { t } = useLaravelReactI18n();
  return (
    <AppLayout title={t('Checks')} breadcrumbs={[{ title: t('Checks'), href: route('checks.index') }]}>
      <PageTitle>{t('Checks')}</PageTitle>
      <div className="mx-auto mt-4 w-full max-w-5xl">
        <DataTable
          headerButton={
            <Button size="sm">
              <Link href={route('checks.create')}>{t('New Check')}</Link>
            </Button>
          }
          sortingState={[{ id: 'date', desc: true }]}
          visibilityState={{ expenseType: false }}
          data={unconfirmedChecks}
          columns={columns}
        />
      </div>
    </AppLayout>
  );
}
