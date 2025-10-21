import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';

import { selectionHeader } from '@/components/custom-ui/datatable/columns';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Button } from '@/components/ui/button';
import { ModelMorphName, SessionName } from '@/enums';
import type { Visit } from '@/types/models/visit';
import { router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { useMemo, useState } from 'react';
import { EmailHeader } from './_components/EmailHeader';
interface Props {
  visitors: Visit[];
}
export default function Index({ visitors }: Props) {
  const [selectedVisitors, setSelectedVisitors] = useState<string[]>([]);
  const { t } = useTranslations();
  const columns: ColumnDef<Visit>[] = useMemo(
    () => [
      selectionHeader as ColumnDef<Visit>,
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
    ],
    [],
  );

  function handleNewEmail() {
    router.post(
      route('session', {
        name: SessionName.EMAIL_RECIPIENTS,
        value: {
          type: ModelMorphName.VISIT,
          ids: selectedVisitors,
        },
        redirect_to: 'communication.emails.create',
      }),
    );
  }
  return (
    <AppLayout
      title={t('Send email to :name', { name: t('Visitors') })}
      breadcrumbs={[{ title: t('Communication') }, { title: t('Emails'), href: route('communication.emails.index') }, { title: t('Visitors') }]}
    >
      <EmailHeader name={t('Visitors')} />

      <div className="mx-auto w-full max-w-2xl">
        <DataTable
          headerButton={
            <Button disabled={selectedVisitors.length === 0} size="sm" onClick={handleNewEmail}>
              {t('New :model', { model: t('Email') })}
            </Button>
          }
          onSelectedRowsChange={setSelectedVisitors}
          data={visitors}
          rowId="id"
          columns={columns}
        />
      </div>
    </AppLayout>
  );
}
