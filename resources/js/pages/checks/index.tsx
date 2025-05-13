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
  confirmedChecks: Check[];
}
export default function Index({ unconfirmedChecks, confirmedChecks }: IndexPageProps) {
  const { t } = useLaravelReactI18n();
  return (
    <AppLayout title={t('Checks')} breadcrumbs={[{ title: t('Checks'), href: route('checks.index') }]}>
      <header className="space-y-2">
        <PageTitle>{t('Checks')}</PageTitle>
        <Button size="sm">
          <Link href={route('checks.create')}>{t('New :model',{model:t('Check')})}</Link>
        </Button>
      </header>
      <section className="mx-auto mt-4 w-full max-w-5xl space-y-16">
        <div>
          <PageTitle className="text-left text-xl font-semibold">{t('Unconfirmed Checks')}</PageTitle>
          <DataTable
            sortingState={[{ id: 'date', desc: true }]}
            visibilityState={{ expenseType: false }}
            data={unconfirmedChecks}
            columns={columns}
          />
        </div>
        <div>
          <PageTitle className="text-left text-xl font-semibold">{t('Confirmed Checks')}</PageTitle>
          <DataTable sortingState={[{ id: 'date', desc: true }]} visibilityState={{ expenseType: false }} data={confirmedChecks} columns={columns} />
        </div>
      </section>
    </AppLayout>
  );
}
