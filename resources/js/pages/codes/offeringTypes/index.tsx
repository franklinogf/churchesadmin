import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { type OfferingType } from '@/types/models/offering-type';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { OfferingTypeForm } from './components/OfferingTypeForm';
import { columns } from './includes/columns';

export default function OfferingTypesIndex({ offeringTypes }: { offeringTypes: OfferingType[] }) {
  const { t } = useLaravelReactI18n();
  return (
    <AppLayout title={t('Offering Types')} breadcrumbs={[{ title: t('Offering Types') }]}>
      <PageTitle>{t('Offering Types')}</PageTitle>
      <DataTable
        headerButton={
          <OfferingTypeForm>
            <Button>{t('Add Offering Type')}</Button>
          </OfferingTypeForm>
        }
        data={offeringTypes}
        columns={columns}
      />
    </AppLayout>
  );
}
