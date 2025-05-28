import { PageTitle } from '@/components/PageTitle';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import type { Email, EmailPivot } from '@/types/models/email';

import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';

import type { SharedData } from '@/types';
import { useEcho } from '@laravel/echo-react';
import { type ColumnDef } from '@tanstack/react-table';
import { useMemo, useState } from 'react';

import { Button } from '@/components/ui/button';
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog';
import { ScrollArea } from '@/components/ui/scroll-area';
import { EmailStatus } from '@/enums';
import type { Member } from '@/types/models/member';
import type { Missionary } from '@/types/models/missionary';

interface EmailsPageProps extends SharedData {
  email: Email;
}
export default function EmailsPage({ email: initialEmail, church }: EmailsPageProps) {
  const { t } = useTranslations();
  const [email, setEmail] = useState<Email>(initialEmail);
  const [datatableData, setDatatableData] = useState<(Member | Missionary)[]>(
    email.recipientsType === 'member' ? email.members! : email.missionaries!,
  );

  useEcho<{ email: Email }>(`${church?.id}.emails.${email.id}`, 'EmailStatusUpdatedEvent', (e) => {
    setEmail({
      ...email,
      status: e.email.status,
      sentAt: e.email.sentAt,
      errorMessage: e.email.errorMessage,
    });
  });

  useEcho<{ pivot: EmailPivot }>(`${church?.id}.emails.${email.id}.emailable`, 'EmailableStatusUpdatedEvent', (e) => {
    setDatatableData((prevData) =>
      prevData.map((recipient) => {
        if (recipient.emailMessage?.id === e.pivot.id) {
          return {
            ...recipient,
            emailMessage: {
              ...recipient.emailMessage,
              status: e.pivot.status,
              sentAt: e.pivot.sentAt,
              errorMessage: e.pivot.errorMessage,
            },
          };
        }
        return recipient;
      }),
    );
  });

  const columns: ColumnDef<Member | Missionary>[] = useMemo(
    () => [
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Recipient')} />,
        accessorKey: 'name',
        enableHiding: false,
        enableColumnFilter: false,
        cell: ({ row }) => <DatatableCell>{`${row.original.name} ${row.original.lastName}`}</DatatableCell>,
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Status')} />,
        accessorKey: 'emailMessage.status',
        enableHiding: false,
        meta: { filterVariant: 'select', translationPrefix: 'enum.email_status.' },
        cell: ({ row }) => (
          <DatatableCell justify="center">
            <Badge
              variant={
                row.original.emailMessage?.status === EmailStatus.SENT
                  ? 'success'
                  : row.original.emailMessage?.status === EmailStatus.FAILED
                    ? 'destructive'
                    : 'secondary'
              }
            >
              {t(`enum.email_status.${row.original.emailMessage?.status ?? 'pending'}`)}
            </Badge>
          </DatatableCell>
        ),
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Sent at')} />,
        accessorKey: 'emailMessage.sentAt',
        enableHiding: false,
        enableColumnFilter: false,
        cell: ({ row }) => <DatatableCell justify="center">{row.original.emailMessage?.sentAt ?? t('Not sent yet')}</DatatableCell>,
      },
    ],

    [t],
  );
  return (
    <AppLayout
      title={t('Emails')}
      breadcrumbs={[{ title: t('Communication') }, { title: t('Emails'), href: route('communication.emails.index') }, { title: email.subject }]}
    >
      <header className="mb-6 flex flex-col items-center gap-2">
        <PageTitle
          description={t('Sent by :name on :date', {
            name: email.sender?.name ?? t('Unknown sender'),
            date: email.sentAt ?? t('Not sent yet'),
          })}
        >
          {email.subject}
        </PageTitle>
        <EmailDetailButton email={email} />
      </header>

      <DataTable data={datatableData} columns={columns} visibilityState={{ attachmentsCount: false }} />
    </AppLayout>
  );
}

function EmailDetailButton({ email }: { email: Email }) {
  const { t } = useTranslations();
  return (
    <Dialog>
      <DialogTrigger asChild>
        <Button variant="outline" size="sm">
          {t('View email')}
        </Button>
      </DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>{email.subject}</DialogTitle>
          <DialogDescription>
            {t('Sent by :name on :date', {
              name: email.sender?.name ?? t('Unknown sender'),
              date: email.sentAt ?? t('Not sent yet'),
            })}
          </DialogDescription>
        </DialogHeader>
        <section className="flex flex-col gap-4">
          <ScrollArea className="max-h-[400px]">
            <div className="prose dark:prose-invert" dangerouslySetInnerHTML={{ __html: email.body }}></div>
          </ScrollArea>
          {email.attachments && email.attachments.length > 0 ? (
            <div>
              <h3 className="text-lg font-semibold">{t('Attachments')}</h3>
              <ul className="list-disc pl-5">
                {email.attachments.map((attachment) => (
                  <li key={attachment.id}>
                    <a href={attachment.url} target="_blank" rel="noopener noreferrer" className="text-blue-600 hover:underline">
                      {attachment.name}
                    </a>
                  </li>
                ))}
              </ul>
            </div>
          ) : (
            <p className="text-muted-foreground">{t('This email has no attachments')}</p>
          )}
        </section>
        <DialogFooter>
          <DialogClose asChild>
            <Button variant="outline">{t('Close')}</Button>
          </DialogClose>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
