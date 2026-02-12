import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import type { Member } from '@/types/models/member';

import EmailController from '@/actions/App/Http/Controllers/Communication/EmailController';
import SessionController from '@/actions/App/Http/Controllers/SessionController';
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
  const { t } = useTranslations();

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
      title={t('Send email to :name', { name: t('Members') })}
      breadcrumbs={[{ title: t('Communication') }, { title: t('Emails'), href: EmailController.index().url }, { title: t('Members') }]}
    >
      <EmailHeader name={t('Members')} />

      <div className="mx-auto w-full max-w-2xl">
        <DataTable
          headerButton={
            <Button disabled={selectedMembers.length === 0} size="sm" onClick={handleNewEmail}>
              {t('New :model', { model: t('Email') })}
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
