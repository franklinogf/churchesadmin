import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';

import { Button } from '@/components/ui/button';

import EmailController from '@/actions/App/Http/Controllers/Communication/EmailController';
import SessionController from '@/actions/App/Http/Controllers/SessionController';
import { ModelMorphName } from '@/enums/ModelMorphName';
import { SessionName } from '@/enums/SessionName';
import type { Missionary } from '@/types/models/missionary';
import { router } from '@inertiajs/react';
import { useState } from 'react';
import { EmailHeader } from './_components/EmailHeader';
import { columns } from './_components/missionaries-columns';
interface Props {
  missionaries: Missionary[];
}
export default function Index({ missionaries }: Props) {
  const [selectedMissionaries, setSelectedMissionaries] = useState<string[]>([]);
  const { t } = useTranslations();

  function handleNewEmail() {
    router.visit(
      SessionController({
        query: {
          name: SessionName.EMAIL_RECIPIENTS,
          value: {
            type: ModelMorphName.MISSIONARY,
            ids: selectedMissionaries,
          },
          redirect_to: 'communication.emails.create',
        },
      }),
    );
  }
  return (
    <AppLayout
      title={t('Send email to :name', { name: t('Missionaries') })}
      breadcrumbs={[{ title: t('Communication') }, { title: t('Emails'), href: EmailController.index().url }, { title: t('Missionaries') }]}
    >
      <EmailHeader name={t('Missionaries')} />

      <div className="mx-auto w-full max-w-2xl">
        <DataTable
          headerButton={
            <Button disabled={selectedMissionaries.length === 0} size="sm" onClick={handleNewEmail}>
              {t('New :model', { model: t('Email') })}
            </Button>
          }
          onSelectedRowsChange={setSelectedMissionaries}
          data={missionaries}
          rowId="id"
          columns={columns}
        />
      </div>
    </AppLayout>
  );
}
