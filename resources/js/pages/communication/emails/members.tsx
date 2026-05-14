import AppLayout from '@/layouts/app-layout';
import type { Member } from '@/types/models/member';
import { useTranslation } from 'react-i18next';

import EmailController from '@/actions/App/Http/Controllers/Communication/EmailController';
import SessionController from '@/actions/App/Http/Controllers/SessionController';
import Datatable from '@/components/datatable/datatable';
import { Button } from '@/components/ui/button';
import { ModelMorphName } from '@/enums/ModelMorphName';
import { SessionName } from '@/enums/SessionName';
import { router } from '@inertiajs/react';
import { useState } from 'react';
import { EmailHeader } from './_components/EmailHeader';
import { columns } from './_components/members-columns';

interface Props {
  members: Member[];
}

export default function Index({ members }: Props) {
  const [selectedMembers, setSelectedMembers] = useState<string[]>([]);

  const { t: tPages } = useTranslation('pages');
  function handleNewEmail() {
    router.visit(
      SessionController({
        query: {
          name: SessionName.EMAIL_RECIPIENTS,
          value: {
            type: ModelMorphName.MEMBER,
            ids: selectedMembers,
          },
          redirect_to: 'communication.emails.create',
        },
      }),
    );
  }
  return (
    <AppLayout
      title={tPages(($) => $.communication.emails.members.sendEmailToName, { name: tPages(($) => $.communication.emails.members.members) })}
      breadcrumbs={[
        { title: tPages(($) => $.communication.emails.members.communication) },
        { title: tPages(($) => $.communication.emails.members.emails), href: EmailController.index().url },
        { title: tPages(($) => $.communication.emails.members.members) },
      ]}
    >
      <EmailHeader name={tPages(($) => $.communication.emails.members.members)} />

      <div className="mx-auto w-full max-w-2xl">
        <Datatable
          renderLeftTop={
            <Button disabled={selectedMembers.length === 0} size="sm" onClick={handleNewEmail}>
              {tPages(($) => $.communication.emails.members.newModel, { model: tPages(($) => $.communication.emails.members.email) })}
            </Button>
          }
          onSelectedRowsChange={setSelectedMembers}
          data={members}
          rowId="id"
          columns={columns}
        />
      </div>
    </AppLayout>
  );
}
