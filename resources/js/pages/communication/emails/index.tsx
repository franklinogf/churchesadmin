import { PageTitle } from '@/components/PageTitle';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import type { Email } from '@/types/models/email';
import { Link } from '@inertiajs/react';

import EmailListMemberController from '@/actions/App/Http/Controllers/Communication/EmailListMemberController';
import EmailListMissionaryController from '@/actions/App/Http/Controllers/Communication/EmailListMissionaryController';
import EmailListVisitorController from '@/actions/App/Http/Controllers/Communication/EmailListVisitorController';
import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import type { SharedData } from '@/types';
import { useEcho } from '@laravel/echo-react';
import { UsersIcon } from 'lucide-react';
import { useState } from 'react';
import { columns } from './_components/index-columns';

interface EmailsPageProps extends SharedData {
  emails: Email[];
}
export default function EmailsPage({ emails: initialEmails, church }: EmailsPageProps) {
  const { t } = useTranslations();
  const [emails, setEmails] = useState<Email[]>(initialEmails);
  const { can: userCan } = useUser();

  useEcho<{ email: Email }>(`${church?.id}.emails`, 'EmailStatusUpdatedEvent', (e) => {
    setEmails((prevEmails) => {
      const updatedEmails = prevEmails.map((email) => {
        if (email.id === e.email.id) {
          return { ...email, ...e.email };
        }
        return email;
      });
      // If the email is not found, add it in front of the list
      if (!updatedEmails.some((email) => email.id === e.email.id)) {
        updatedEmails.unshift(e.email);
      }
      return updatedEmails;
    });
  });

  const recipientTypes = [
    { label: t('Members'), route: EmailListMemberController(), permissionNeeded: TenantPermission.EMAILS_SEND_TO_MEMBERS },
    { label: t('Missionaries'), route: EmailListMissionaryController(), permissionNeeded: TenantPermission.EMAILS_SEND_TO_MISSIONARIES },
    { label: t('Visitors'), route: EmailListVisitorController(), permissionNeeded: TenantPermission.EMAILS_SEND_TO_VISITORS },
  ];

  const filteredRecipientTypes = recipientTypes.filter((type) => {
    return !type.permissionNeeded || userCan(type.permissionNeeded);
  });

  return (
    <AppLayout title={t('Emails')} breadcrumbs={[{ title: t('Communication') }, { title: t('Emails') }]}>
      <PageTitle description={t('Manage your emails here')}>{t('Emails')}</PageTitle>
      <Card className="mx-auto mb-2 max-w-lg">
        <CardHeader>
          <CardTitle>{t('Send a message')}</CardTitle>
          <CardDescription>{t('Select a group below to compose a new message.')}</CardDescription>
        </CardHeader>

        <CardContent className="grid grid-cols-1 gap-2 md:grid-cols-2 lg:grid-cols-3">
          {filteredRecipientTypes.map(({ label, route: url }) => (
            <Button key={label} variant="outline" asChild>
              <Link href={url}>
                <UsersIcon className="size-4" />
                {label}
              </Link>
            </Button>
          ))}
        </CardContent>
      </Card>

      <DataTable data={emails} columns={columns} visibilityState={{ attachmentsCount: false }} />
    </AppLayout>
  );
}
