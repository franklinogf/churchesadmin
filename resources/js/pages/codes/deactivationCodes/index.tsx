import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { DeactivationCodeForm } from '@/components/forms/deactivation-code-form';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import { type DeactivationCode } from '@/types/models/deactivation-code';
import { useState } from 'react';
import { columns } from './includes/columns';

export default function DeactivationCodesIndex({ deactivationCodes }: { deactivationCodes: DeactivationCode[] }) {
  const { t } = useTranslations();
  const [open, setOpen] = useState(false);
  return (
    <AppLayout title={t('Deactivation codes')} breadcrumbs={[{ title: t('Deactivation codes') }]}>
      <PageTitle>{t('Deactivation codes')}</PageTitle>
      <DeactivationCodeForm open={open} setOpen={setOpen} />
      <div className="mx-auto w-full max-w-xl">
        <DataTable
          headerButton={
            <Button size="sm" onClick={() => setOpen(true)}>
              {t('Add :model', { model: t('Deactivation code') })}
            </Button>
          }
          data={deactivationCodes}
          columns={columns}
        />
      </div>
    </AppLayout>
  );
}
