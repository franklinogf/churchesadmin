import EmailController from '@/actions/App/Http/Controllers/Communication/EmailController';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/data-table-actions-dropdown';
import { DatatableBadgeCell, DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { useLocaleDate } from '@/hooks/use-locale-date';
import { useTranslations } from '@/hooks/use-translations';
import { useUser } from '@/hooks/use-user';
import type { Email } from '@/types/models/email';
import { Link } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Users2Icon } from 'lucide-react';
import { useState } from 'react';
import { ErrorMessageDialog } from './error-message-dialog';

export const columns: ColumnDef<Email>[] = [
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Sender" />,
    accessorKey: 'sender',
    enableHiding: false,
    enableColumnFilter: false,
    cell: function CellComponent({ row }) {
      const { user } = useUser();
      const { t } = useTranslations();
      return <DatatableCell>{user.id === row.original.senderId ? t('You') : row.original.sender?.name}</DatatableCell>;
    },
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Subject" />,
    accessorKey: 'subject',
    enableHiding: false,
    enableColumnFilter: false,
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Status" />,
    accessorKey: 'status',
    enableHiding: false,
    meta: { filterVariant: 'select', translationPrefix: 'enum.email_status.' },
    cell: function CellComponent({ row }) {
      const { t } = useTranslations();
      return (
        <DatatableCell justify="center">
          <Badge variant={row.original.status === 'sent' ? 'success' : row.original.status === 'failed' ? 'destructive' : 'secondary'}>
            {t(`enum.email_status.${row.original.status}`)}
          </Badge>
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Sent at" />,
    accessorKey: 'sentAt',
    enableHiding: false,
    enableColumnFilter: false,
    cell: function CellComponent({ row }) {
      const { t } = useTranslations();
      const { formatLocaleDate } = useLocaleDate();
      return <DatatableCell justify="center">{row.original.sentAt ? formatLocaleDate(row.original.sentAt) : t('Not sent yet')}</DatatableCell>;
    },
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Recipients type" />,
    accessorKey: 'recipientsType',
    enableHiding: false,
    meta: { filterVariant: 'select', translationPrefix: 'enum.model_morph_name.' },
    cell: function CellComponent({ row }) {
      const { t } = useTranslations();
      return (
        <DatatableCell justify="center">
          <Badge>{t(`enum.model_morph_name.${row.original.recipientsType}`)}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DataTableColumnHeader column={column} title="Attachments" />,
    accessorKey: 'attachmentsCount',
    enableColumnFilter: false,
    cell: function CellComponent({ row }) {
      return <DatatableBadgeCell>{row.original.attachmentsCount}</DatatableBadgeCell>;
    },
  },
  {
    id: 'actions',
    enableHiding: false,
    enableSorting: false,
    size: 0,
    cell: function CellComponent({ row }) {
      const [open, setOpen] = useState(false);
      const { t } = useTranslations();
      return (
        <>
          <ErrorMessageDialog email={row.original} open={open} setOpen={setOpen} />
          <DatatableActionsDropdown>
            <DropdownMenuItem asChild>
              <Link href={EmailController.show(row.original.id).url}>
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
];
