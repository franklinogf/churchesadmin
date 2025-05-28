import { PageTitle } from '@/components/PageTitle';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import type { Email } from '@/types/models/email';
import { Link } from '@inertiajs/react';

import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/data-table-actions-dropdown';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { ScrollArea } from '@/components/ui/scroll-area';
import { UserPermission } from '@/enums/user';
import { useUser } from '@/hooks/use-user';
import type { SharedData } from '@/types';
import { useEcho } from '@laravel/echo-react';
import { type ColumnDef } from '@tanstack/react-table';
import { Users2Icon, UsersIcon } from 'lucide-react';
import { useMemo, useState } from 'react';

const recipientTypes = [
  { label: 'Members', route: 'communication.emails.members', permissionNeeded: UserPermission.EMAILS_SEND_TO_MEMBERS },
  { label: 'Missionaries', route: 'communication.emails.missionaries', permissionNeeded: UserPermission.EMAILS_SEND_TO_MISSIONARIES },
];

interface EmailsPageProps extends SharedData {
  emails: Email[]; // Adjust type as needed
}
export default function EmailsPage({ emails: initialEmails, auth: { user }, church }: EmailsPageProps) {
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

  const columns: ColumnDef<Email>[] = useMemo(
    () => [
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Sender')} />,
        accessorKey: 'sender',
        enableHiding: false,
        enableColumnFilter: false,
        cell: ({ row }) => <DatatableCell>{user.id === row.original.senderId ? t('You') : row.original.sender?.name}</DatatableCell>,
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Subject')} />,
        accessorKey: 'subject',
        enableHiding: false,
        enableColumnFilter: false,
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Status')} />,
        accessorKey: 'status',
        enableHiding: false,
        meta: { filterVariant: 'select', translationPrefix: 'enum.email_status.' },
        cell: ({ row }) => (
          <DatatableCell justify="center">
            <Badge variant={row.original.status === 'sent' ? 'success' : row.original.status === 'failed' ? 'destructive' : 'secondary'}>
              {t(`enum.email_status.${row.original.status}`)}
            </Badge>
          </DatatableCell>
        ),
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Sent at')} />,
        accessorKey: 'sentAt',
        enableHiding: false,
        enableColumnFilter: false,
        cell: ({ row }) => <DatatableCell justify="center">{row.original.sentAt ?? t('Not sent yet')}</DatatableCell>,
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Recipients type')} />,
        accessorKey: 'recipientsType',
        enableHiding: false,
        meta: { filterVariant: 'select', translationPrefix: 'enum.model_morph_name.' },
        cell: ({ row }) => (
          <DatatableCell justify="center">
            <Badge>{t(`enum.model_morph_name.${row.original.recipientsType}`)}</Badge>
          </DatatableCell>
        ),
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Attachments')} />,
        accessorKey: 'attachmentsCount',
        enableColumnFilter: false,
        cell: ({ row }) => (
          <DatatableCell justify="center">
            <Badge variant="secondary">{row.original.attachmentsCount}</Badge>
          </DatatableCell>
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
              <ErrorMessageDialog email={row.original} open={open} setOpen={setOpen} />
              <DatatableActionsDropdown>
                <DropdownMenuItem asChild>
                  <Link href={route('communication.emails.show', row.original.id)}>
                    <Users2Icon className="size-4" />
                    <span>{t('View recipients')}</span>
                  </Link>
                </DropdownMenuItem>
                <DropdownMenuItem onSelect={() => setOpen(true)}>{t('View error')}</DropdownMenuItem>
              </DatatableActionsDropdown>
            </>
          );
        },
      },
    ],

    [t, user.id],
  );

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
              <Link href={route(url)}>
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

function ErrorMessageDialog({ email, open, setOpen }: { email: Email; open: boolean; setOpen: (open: boolean) => void }) {
  const { t } = useTranslations();
  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>{t('Error message')}</DialogTitle>
          <DialogDescription>{t('Email error if any')}</DialogDescription>
        </DialogHeader>
        <div className="flex flex-col gap-4">
          <ScrollArea className="max-h-[400px]">
            <div className="prose dark:prose-invert">
              <pre className="w-full max-w-full text-wrap">{email.errorMessage ?? t('No error message available')}</pre>
            </div>
          </ScrollArea>
        </div>
        <DialogFooter>
          <DialogClose asChild>
            <Button variant="outline">{t('Close')}</Button>
          </DialogClose>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
