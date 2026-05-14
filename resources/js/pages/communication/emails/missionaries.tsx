import AppLayout from '@/layouts/app-layout';
import { useTranslation } from 'react-i18next';

import { Button } from '@/components/ui/button';

import EmailController from '@/actions/App/Http/Controllers/Communication/EmailController';
import SessionController from '@/actions/App/Http/Controllers/SessionController';
import Datatable from '@/components/datatable/datatable';
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

  const { t: tPages } = useTranslation('pages');
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
      title={tPages(($) => $.communication.emails.missionaries.sendEmailToName, {
        name: tPages(($) => $.communication.emails.missionaries.missionaries),
      })}
      breadcrumbs={[
        { title: tPages(($) => $.communication.emails.missionaries.communication) },
        { title: tPages(($) => $.communication.emails.missionaries.emails), href: EmailController.index().url },
        { title: tPages(($) => $.communication.emails.missionaries.missionaries) },
      ]}
    >
      <EmailHeader name={tPages(($) => $.communication.emails.missionaries.missionaries)} />

      <div className="mx-auto w-full max-w-2xl">
        <Datatable
          renderLeftTop={
            <Button disabled={selectedMissionaries.length === 0} size="sm" onClick={handleNewEmail}>
              {tPages(($) => $.communication.emails.missionaries.newModel, { model: tPages(($) => $.communication.emails.missionaries.email) })}
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
