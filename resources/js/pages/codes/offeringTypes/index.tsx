import { Datatable } from '@/components/datatable/datatable';
import { OfferingTypeForm } from '@/components/forms/offering-type-form';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { type OfferingType } from '@/types/models/offering-type';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { columns } from './includes/columns';

export default function OfferingTypesIndex({ offeringTypes }: { offeringTypes: OfferingType[] }) {
  const { t: tPages } = useTranslation('pages');
  const [open, setOpen] = useState(false);
  return (
    <AppLayout
      title={tPages(($) => $.codes.offeringTypes.index.offeringTypes)}
      breadcrumbs={[{ title: tPages(($) => $.codes.offeringTypes.index.offeringTypes) }]}
    >
      <PageTitle>{tPages(($) => $.codes.offeringTypes.index.offeringTypes)}</PageTitle>
      <OfferingTypeForm open={open} setOpen={setOpen} />
      <div className="mx-auto w-full max-w-xl">
        <Datatable
          renderLeftTop={
            <Button size="sm" onClick={() => setOpen(true)}>
              {tPages(($) => $.codes.offeringTypes.index.addModel, { model: tPages(($) => $.codes.offeringTypes.index.offeringType) })}
            </Button>
          }
          data={offeringTypes}
          columns={columns}
        />
      </div>
    </AppLayout>
  );
}
