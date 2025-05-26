import { PageTitle } from '@/components/PageTitle';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import type { Email } from '@/types/models/email';
import { Link } from '@inertiajs/react';

import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import type { SharedData } from '@/types';
import { useEcho } from '@laravel/echo-react';
import { type ColumnDef } from '@tanstack/react-table';
import { useMemo, useState } from 'react';

interface EmailsPageProps extends SharedData {
  emails: Email[]; // Adjust type as needed
}
export default function EmailsPage({ emails: initialEmails, auth: { user } }: EmailsPageProps) {
  const { t } = useTranslations();
  const [emails, setEmails] = useState<Email[]>(initialEmails);
  useEcho<{ email: Email }>('test-church.emails', 'EmailStatusUpdatedEvent', (e) => {
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
    ],

    [t, user.id],
  );
  return (
    <AppLayout title="Emails" breadcrumbs={[{ title: t('Communication') }, { title: t('Emails') }]}>
      <PageTitle description="Manage your emails here.">Emails</PageTitle>
      <Link href={route('communication.emails.members')}>Email members</Link>
      <Link href={route('communication.emails.missionaries')}>Email Missionaries</Link>

      <DataTable data={emails} columns={columns} visibilityState={{ attachmentsCount: false }} />
    </AppLayout>
  );
}
