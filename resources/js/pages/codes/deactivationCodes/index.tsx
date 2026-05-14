import Datatable from '@/components/datatable/datatable';
import { DeactivationCodeForm } from '@/components/forms/deactivation-code-form';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { type DeactivationCode } from '@/types/models/deactivation-code';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { columns } from './includes/columns';

export default function DeactivationCodesIndex({ deactivationCodes }: { deactivationCodes: DeactivationCode[] }) {
  const { t: tPages } = useTranslation('pages');
  const [open, setOpen] = useState(false);
  return (
    <AppLayout
      title={tPages(($) => $.codes.deactivationCodes.index.deactivationCodes)}
      breadcrumbs={[{ title: tPages(($) => $.codes.deactivationCodes.index.deactivationCodes) }]}
    >
      <PageTitle>{tPages(($) => $.codes.deactivationCodes.index.deactivationCodes)}</PageTitle>
      <DeactivationCodeForm open={open} setOpen={setOpen} />
      <div className="mx-auto w-full max-w-xl">
        <Datatable
          renderLeftTop={
            <Button size="sm" onClick={() => setOpen(true)}>
              {tPages(($) => $.codes.deactivationCodes.index.addModel, { model: tPages(($) => $.codes.deactivationCodes.index.deactivationCode) })}
            </Button>
          }
          data={deactivationCodes}
          columns={columns}
        />
      </div>
    </AppLayout>
  );
}
