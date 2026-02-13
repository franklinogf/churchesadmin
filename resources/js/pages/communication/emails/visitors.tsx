import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';

import EmailController from '@/actions/App/Http/Controllers/Communication/EmailController';
import SessionController from '@/actions/App/Http/Controllers/SessionController';
import { Button } from '@/components/ui/button';
import { ModelMorphName } from '@/enums/ModelMorphName';
import { SessionName } from '@/enums/SessionName';
import type { Visit } from '@/types/models/visit';
import { router } from '@inertiajs/react';
import { useState } from 'react';
import { EmailHeader } from './_components/EmailHeader';
import { columns } from './_components/visitors-columns';
interface Props {
  visitors: Visit[];
}
export default function Index({ visitors }: Props) {
  const [selectedVisitors, setSelectedVisitors] = useState<string[]>([]);
  const { t } = useTranslations();

  function handleNewEmail() {
    router.visit(
      SessionController({
        query: {
          name: SessionName.EMAIL_RECIPIENTS,
          value: {
            type: ModelMorphName.VISIT,
            ids: selectedVisitors,
          },
          redirect_to: 'communication.emails.create',
        },
      }),
    );
  }
  return (
    <AppLayout
      title={t('Send email to :name', { name: t('Visitors') })}
      breadcrumbs={[{ title: t('Communication') }, { title: t('Emails'), href: EmailController.index().url }, { title: t('Visitors') }]}
    >
      <EmailHeader name={t('Visitors')} />

      <div className="mx-auto w-full max-w-2xl">
        <DataTable
          headerButton={
            <Button disabled={selectedVisitors.length === 0} size="sm" onClick={handleNewEmail}>
              {t('New :model', { model: t('Email') })}
            </Button>
          }
          onSelectedRowsChange={setSelectedVisitors}
          data={visitors}
          rowId="id"
          columns={columns}
        />
      </div>
    </AppLayout>
  );
}
