import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { OfferingTypeForm } from '@/components/forms/offering-type-form';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { type OfferingType } from '@/types/models/offering-type';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { useState } from 'react';
import { columns } from './includes/columns';

export default function OfferingTypesIndex({ offeringTypes }: { offeringTypes: OfferingType[] }) {
  const { t } = useLaravelReactI18n();
  const [open, setOpen] = useState(false);
  return (
    <AppLayout title={t('Offering types')} breadcrumbs={[{ title: t('Offering types') }]}>
      <PageTitle>{t('Offering types')}</PageTitle>
      <OfferingTypeForm open={open} setOpen={setOpen} />
      <div className="mx-auto w-full max-w-xl">
        <DataTable
          headerButton={
            <Button size="sm" onClick={() => setOpen(true)}>
              {t('Add :model', { model: t('Offering type') })}
            </Button>
          }
          data={offeringTypes}
          columns={columns}
        />
      </div>
    </AppLayout>
  );
}
