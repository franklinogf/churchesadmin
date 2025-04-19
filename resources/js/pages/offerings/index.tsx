import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';

import { PageTitle } from '@/components/PageTitle';
import { OfferingTransaction } from '@/types/models/transaction';
import { Link } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { columns } from './includes/columns';

interface IndexPageProps {
  offerings: OfferingTransaction[];
}
export default function Index({ offerings }: IndexPageProps) {
  const { t } = useLaravelReactI18n();
  return (
    <AppLayout title={t('Offerings')} breadcrumbs={[{ title: t('Offerings') }]}>
      <PageTitle>{t('Offerings')}</PageTitle>
      <DataTable
        headerButton={
          <Button asChild>
            <Link href={route('offerings.create')}>{t('New Offering')}</Link>
          </Button>
        }
        data={offerings}
        columns={columns}
        sortingState={[{ id: 'date', desc: true }]}
        visibilityState={{ confirmed: false }}
      />
    </AppLayout>
  );
}
