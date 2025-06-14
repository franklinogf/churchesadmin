import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import type { Member } from '@/types/models/member';

import { selectionHeader } from '@/components/custom-ui/datatable/columns';
import { DatatableBadgeCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Button } from '@/components/ui/button';
import { ModelMorphName, SessionName } from '@/enums';
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
        header: ({ column }) => <DataTableColumnHeader column={column} title="Name" />,
        accessorKey: 'name',
        enableHiding: false,
        enableColumnFilter: false,
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title="Last name" />,
        accessorKey: 'lastName',
        enableHiding: false,
        enableColumnFilter: false,
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title="Gender" />,
        accessorKey: 'gender',
        filterFn: 'equalsString',
        meta: { filterVariant: 'select', translationPrefix: 'enum.gender.' },
        cell: function CellComponent({ row }) {
          const { t } = useTranslations();
          return <DatatableBadgeCell className="w-24">{t(`enum.gender.${row.original.gender}`)}</DatatableBadgeCell>;
        },
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title="Civil status" />,
        accessorKey: 'civilStatus',
        filterFn: 'equalsString',
        meta: { filterVariant: 'select', translationPrefix: 'enum.civil_status.' },
        cell: function CellComponent({ row }) {
          const { t } = useTranslations();
          return <DatatableBadgeCell className="w-24">{t(`enum.civil_status.${row.original.civilStatus}`)}</DatatableBadgeCell>;
        },
      },
    ],
    [],
  );

  function handleNewEmail() {
    router.post(
      route('session', {
        name: SessionName.EMAIL_RECIPIENTS,
        value: {
          type: ModelMorphName.MEMBER,
          ids: selectedMembers,
        },
        redirect_to: 'communication.emails.create',
      }),
    );
  }
  return (
    <AppLayout
      title={t('Send email to members')}
      breadcrumbs={[{ title: t('Communication') }, { title: t('Emails'), href: route('communication.emails.index') }, { title: t('Members') }]}
    >
      <PageTitle description={t('Select the members you want to send a message to')}>{t('Send email to members')}</PageTitle>

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
