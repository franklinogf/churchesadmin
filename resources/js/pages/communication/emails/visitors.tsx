import AppLayout from '@/layouts/app-layout';
import { useTranslation } from 'react-i18next';

import EmailController from '@/actions/App/Http/Controllers/Communication/EmailController';
import SessionController from '@/actions/App/Http/Controllers/SessionController';
import Datatable from '@/components/datatable/datatable';
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

  const { t: tPages } = useTranslation('pages');
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
      title={tPages(($) => $.communication.emails.visitors.sendEmailToName, { name: tPages(($) => $.communication.emails.visitors.visitors) })}
      breadcrumbs={[
        { title: tPages(($) => $.communication.emails.visitors.communication) },
        { title: tPages(($) => $.communication.emails.visitors.emails), href: EmailController.index().url },
        { title: tPages(($) => $.communication.emails.visitors.visitors) },
      ]}
    >
      <EmailHeader name={tPages(($) => $.communication.emails.visitors.visitors)} />

      <div className="mx-auto w-full max-w-2xl">
        <Datatable
          renderLeftTop={
            <Button disabled={selectedVisitors.length === 0} size="sm" onClick={handleNewEmail}>
              {tPages(($) => $.communication.emails.visitors.newModel, { model: tPages(($) => $.communication.emails.visitors.email) })}
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
