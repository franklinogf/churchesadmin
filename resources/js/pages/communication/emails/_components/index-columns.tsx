import EmailController from '@/actions/App/Http/Controllers/Communication/EmailController';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/datatable-actions-dropdown';
import { DatatableBadgeCell, DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { Badge } from '@/components/ui/badge';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { useLocaleDate } from '@/hooks/use-locale-date';
import { useUser } from '@/hooks/use-user';
import type { Email } from '@/types/models/email';
import { Link } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Users2Icon } from 'lucide-react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { ErrorMessageDialog } from './error-message-dialog';

export const columns: ColumnDef<Email>[] = [
  {
    header: ({ column }) => <DatatableHeader column={column} title="Sender" />,
    accessorKey: 'sender',
    enableHiding: false,
    enableColumnFilter: false,
    cell: function CellComponent({ row }) {
      const { user } = useUser();
      const { t: tPages } = useTranslation('pages');
      return (
        <DatatableCell>
          {user.id === row.original.senderId ? tPages(($) => $.communication.emails.components.indexColumns.you) : row.original.sender?.name}
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Subject" />,
    accessorKey: 'subject',
    enableHiding: false,
    enableColumnFilter: false,
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Status" />,
    accessorKey: 'status',
    enableHiding: false,
    meta: { filterVariant: 'select', translationPrefix: 'enum:emailStatus.' },
    cell: function CellComponent({ row }) {
      const { t: tEnum } = useTranslation('enum');
      return (
        <DatatableCell justify="center">
          <Badge variant={row.original.status === 'sent' ? 'success' : row.original.status === 'failed' ? 'destructive' : 'secondary'}>
            {tEnum(($) => $.emailStatus[row.original.status])}
          </Badge>
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Sent at" />,
    accessorKey: 'sentAt',
    enableHiding: false,
    enableColumnFilter: false,
    cell: function CellComponent({ row }) {
      const { t: tPages } = useTranslation('pages');
      const { formatLocaleDate } = useLocaleDate();
      return (
        <DatatableCell justify="center">
          {row.original.sentAt ? formatLocaleDate(row.original.sentAt) : tPages(($) => $.communication.emails.components.indexColumns.notSentYet)}
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Recipients type" />,
    accessorKey: 'recipientsType',
    enableHiding: false,
    meta: { filterVariant: 'select', translationPrefix: 'enum:modelMorphName.' },
    cell: function CellComponent({ row }) {
      const { t: tEnum } = useTranslation('enum');
      return (
        <DatatableCell justify="center">
          <Badge>{tEnum(($) => $.modelMorphName[row.original.recipientsType])}</Badge>
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Attachments" />,
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
      const { t: tPages } = useTranslation('pages');
      return (
        <>
          <ErrorMessageDialog email={row.original} open={open} setOpen={setOpen} />
          <DatatableActionsDropdown>
            <DropdownMenuItem asChild>
              <Link href={EmailController.show(row.original.id).url}>
                <Users2Icon className="size-4" />
                <span>{tPages(($) => $.communication.emails.components.indexColumns.viewRecipients)}</span>
              </Link>
            </DropdownMenuItem>
            <DropdownMenuItem onSelect={() => setOpen(true)}>
              {tPages(($) => $.communication.emails.components.indexColumns.viewError)}
            </DropdownMenuItem>
          </DatatableActionsDropdown>
        </>
      );
    },
  },
];
