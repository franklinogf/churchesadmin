import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import type { Member } from '@/types/models/member';

import { selectionHeader } from '@/components/custom-ui/datatable/columns';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { SessionName } from '@/enums';
import { router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { useMemo, useState } from 'react';
interface Props {
  members: Member[];
}
export default function Index({ members }: Props) {
  const [selectedMembers, setSelectedMembers] = useState<string[]>([]);
  const { t } = useTranslations();
  const columns: ColumnDef<Member>[] = useMemo(
    () => [
      selectionHeader as ColumnDef<Member>,
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Name')} />,
        accessorKey: 'name',
        enableHiding: false,
        enableColumnFilter: false,
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Last name')} />,
        accessorKey: 'lastName',
        enableHiding: false,
        enableColumnFilter: false,
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Gender')} />,
        accessorKey: 'gender',
        filterFn: 'equalsString',
        meta: { filterVariant: 'select', translationPrefix: 'enum.gender.' },
        cell: function CellComponent({ row }) {
          const { t } = useTranslations();
          return (
            <DatatableCell justify="center">
              <Badge className="w-24">{t(`enum.gender.${row.original.gender}`)}</Badge>
            </DatatableCell>
          );
        },
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Civil status')} />,
        accessorKey: 'civilStatus',
        filterFn: 'equalsString',
        meta: { filterVariant: 'select', translationPrefix: 'enum.civil_status.' },
        cell: function CellComponent({ row }) {
          const { t } = useTranslations();
          return (
            <DatatableCell justify="center">
              <Badge className="w-24">{t(`enum.civil_status.${row.original.civilStatus}`)}</Badge>
            </DatatableCell>
          );
        },
      },
    ],
    [t],
  );

  function handleNewEmail() {
    router.post(route('session', { name: SessionName.EMAIL_MEMBERS_IDS, value: selectedMembers, redirect_to: 'messages.members.create' }));
  }
  return (
    <AppLayout title={t('Send email to members')} breadcrumbs={[{ title: t('Members'), href: route('members.index') }]}>
      <PageTitle description={t('Select the members you want to send a message to')}>{t('Send email to members')}</PageTitle>

      <div className="mx-auto w-full max-w-2xl">
        <DataTable
          headerButton={
            <Button disabled={selectedMembers.length === 0} size="sm" onClick={handleNewEmail}>
              {t('New email')}
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
