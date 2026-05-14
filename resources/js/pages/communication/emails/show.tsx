import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import type { Email, EmailPivot } from '@/types/models/email';
import { useTranslation } from 'react-i18next';

import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { Badge } from '@/components/ui/badge';

import type { SharedData } from '@/types';
import { useEcho } from '@laravel/echo-react';
import { type ColumnDef } from '@tanstack/react-table';
import { useMemo, useState } from 'react';

import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/datatable-actions-dropdown';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
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
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { ScrollArea } from '@/components/ui/scroll-area';

import EmailController from '@/actions/App/Http/Controllers/Communication/EmailController';
import EmailRetryController from '@/actions/App/Http/Controllers/Communication/EmailRetryController';
import Datatable from '@/components/datatable/datatable';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { EmailStatus } from '@/enums/EmailStatus';
import type { Member } from '@/types/models/member';
import type { Missionary } from '@/types/models/missionary';
import type { Visit } from '@/types/models/visit';
import { Link } from '@inertiajs/react';
import { AlertCircleIcon } from 'lucide-react';

interface EmailsPageProps extends SharedData {
  email: Email;
}
export default function EmailsPage({ email: initialEmail, church }: EmailsPageProps) {
  const { t: tEnum } = useTranslation('enum');
  const { t: tPages } = useTranslation('pages');
  const [email, setEmail] = useState<Email>(initialEmail);
  const [datatableData, setDatatableData] = useState<(Member | Missionary | Visit)[]>(email.recipients);
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

  const columns: ColumnDef<Member | Missionary | Visit>[] = useMemo(
    () => [
      {
        header: ({ column }) => <DatatableHeader column={column} title="Recipient" />,
        accessorKey: 'name',
        enableHiding: false,
        enableColumnFilter: false,
        cell: ({ row }) => <DatatableCell>{`${row.original.name} ${row.original.lastName}`}</DatatableCell>,
      },
      {
        header: ({ column }) => <DatatableHeader column={column} title="Status" />,
        accessorKey: 'emailMessage.status',
        enableHiding: false,
        meta: { filterVariant: 'select', translationPrefix: 'enum:emailStatus.' },
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
              {tEnum(($) => $.emailStatus[row.original.emailMessage?.status ?? 'pending'])}
            </Badge>
          </DatatableCell>
        ),
      },
      {
        header: ({ column }) => <DatatableHeader column={column} title="Sent at" />,
        accessorKey: 'emailMessage.sentAt',
        enableHiding: false,
        enableColumnFilter: false,
        cell: ({ row }) => (
          <DatatableCell justify="center">{row.original.emailMessage?.sentAt ?? tPages(($) => $.communication.emails.show.notSentYet)}</DatatableCell>
        ),
      },
      {
        id: 'actions',
        enableHiding: false,
        enableSorting: false,
        size: 0,
        cell: function CellComponent({ row }) {
          const [open, setOpen] = useState(false);
          return (
            <>
              <ErrorMessageDialog recipient={row.original} open={open} setOpen={setOpen} />
              <DatatableActionsDropdown>
                <DropdownMenuItem onSelect={() => setOpen(true)}>{tPages(($) => $.communication.emails.show.viewError)}</DropdownMenuItem>
              </DatatableActionsDropdown>
            </>
          );
        },
      },
    ],

    [tEnum, tPages],
  );

  const existsFailingEmails = datatableData.some((recipient) => recipient.emailMessage?.status === EmailStatus.FAILED);

  function handleRetryEmail() {
    // the failed email changes to pending, so we can retry sending it
    setDatatableData((prevData) =>
      prevData.map((recipient) => {
        if (recipient.emailMessage?.status === EmailStatus.FAILED) {
          return {
            ...recipient,
            emailMessage: {
              ...recipient.emailMessage,
              status: EmailStatus.PENDING,
              sentAt: null,
              errorMessage: null,
            },
          };
        }
        return recipient;
      }),
    );
  }
  return (
    <AppLayout
      title={tPages(($) => $.communication.emails.show.emails)}
      breadcrumbs={[
        { title: tPages(($) => $.communication.emails.show.communication) },
        { title: tPages(($) => $.communication.emails.show.emails), href: EmailController.index().url },
        { title: email.subject },
      ]}
    >
      <header className="mb-6 flex flex-col items-center gap-2">
        <PageTitle
          description={tPages(($) => $.communication.emails.show.sentByNameOnDate, {
            name: email.sender?.name ?? tPages(($) => $.communication.emails.show.unknownSender),
            date: email.sentAt ?? tPages(($) => $.communication.emails.show.notSentYet),
          })}
        >
          {email.subject}
        </PageTitle>
        <EmailDetailButton email={email} />
      </header>

      {existsFailingEmails && (
        <Alert variant="destructive" className="mx-auto mb-6 max-w-xl">
          <AlertCircleIcon className="size-4" />
          <AlertTitle>{tPages(($) => $.communication.emails.show.someEmailsFailedToSend)}</AlertTitle>
          <AlertDescription>{tPages(($) => $.communication.emails.show.someRecipientsHaveFailedToReceiveTheEmailPlease)}</AlertDescription>

          <div className="col-span-full flex justify-end">
            <Button asChild variant="secondary" size="sm" className="mt-2 ml-auto">
              <Link onClick={handleRetryEmail} method="post" href={EmailRetryController({ email: email.id })}>
                {tPages(($) => $.communication.emails.show.retrySendingFailedEmails)}
              </Link>
            </Button>
          </div>
        </Alert>
      )}

      <Datatable data={datatableData} columns={columns} visibilityState={{ attachmentsCount: false }} />
    </AppLayout>
  );
}

function EmailDetailButton({ email }: { email: Email }) {
  const { t: tPages } = useTranslation('pages');
  return (
    <Dialog>
      <DialogTrigger asChild>
        <Button variant="outline" size="sm">
          {tPages(($) => $.communication.emails.show.viewEmail)}
        </Button>
      </DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>{email.subject}</DialogTitle>
          <DialogDescription>
            {tPages(($) => $.communication.emails.show.sentByNameOnDate, {
              name: email.sender?.name ?? tPages(($) => $.communication.emails.show.unknownSender),
              date: email.sentAt ?? tPages(($) => $.communication.emails.show.notSentYet),
            })}
          </DialogDescription>
        </DialogHeader>
        <section className="flex flex-col gap-4">
          <ScrollArea className="max-h-100">
            <div className="prose dark:prose-invert" dangerouslySetInnerHTML={{ __html: email.body }}></div>
          </ScrollArea>
          {email.attachments && email.attachments.length > 0 ? (
            <div>
              <h3 className="text-lg font-semibold">{tPages(($) => $.communication.emails.show.attachments)}</h3>
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
            <p className="text-muted-foreground">{tPages(($) => $.communication.emails.show.thisEmailHasNoAttachments)}</p>
          )}
        </section>
        <DialogFooter>
          <DialogClose asChild>
            <Button variant="outline">{tPages(($) => $.communication.emails.show.close)}</Button>
          </DialogClose>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}

function ErrorMessageDialog({
  recipient,
  open,
  setOpen,
}: {
  recipient: Member | Missionary | Visit;
  open: boolean;
  setOpen: (open: boolean) => void;
}) {
  const { t: tPages } = useTranslation('pages');
  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogContent className="sm:max-w-150">
        <DialogHeader>
          <DialogTitle>{tPages(($) => $.communication.emails.show.errorMessage)}</DialogTitle>
          <DialogDescription>{tPages(($) => $.communication.emails.show.emailErrorIfAny)}</DialogDescription>
        </DialogHeader>
        <div className="flex flex-col gap-4">
          <ScrollArea className="max-h-100 overflow-hidden">
            <div className="prose dark:prose-invert">
              <pre className="max-w-full text-balance">
                {recipient.emailMessage?.errorMessage ?? tPages(($) => $.communication.emails.show.noErrorMessageAvailable)}
              </pre>
            </div>
          </ScrollArea>
          <div>
            <p className="text-muted-foreground text-sm">
              {tPages(($) => $.communication.emails.show.sentToName, { name: recipient.email ?? tPages(($) => $.communication.emails.show.noData) })}
            </p>
          </div>
        </div>
        <DialogFooter>
          <DialogClose asChild>
            <Button variant="outline">{tPages(($) => $.communication.emails.show.close)}</Button>
          </DialogClose>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
