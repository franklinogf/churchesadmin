import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';

import { selectionHeader } from '@/components/custom-ui/datatable/columns';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

import { ModelMorphName } from '@/enums/ModelMorphName';
import { SessionName } from '@/enums/SessionName';
import type { Missionary } from '@/types/models/missionary';
import { router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { useMemo, useState } from 'react';
import { EmailHeader } from './_components/EmailHeader';
interface Props {
  missionaries: Missionary[];
}
export default function Index({ missionaries }: Props) {
  const [selectedMissionaries, setSelectedMissionaries] = useState<string[]>([]);
  const { t } = useTranslations();
  const columns: ColumnDef<Missionary>[] = useMemo(
    () => [
      selectionHeader as ColumnDef<Missionary>,
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
          return (
            <DatatableCell justify="center">
              <Badge className="w-24">{t(`enum.gender.${row.original.gender}`)}</Badge>
            </DatatableCell>
          );
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
          type: ModelMorphName.MISSIONARY,
          ids: selectedMissionaries,
        },
        redirect_to: 'communication.emails.create',
      }),
    );
  }
  return (
    <AppLayout
      title={t('Send email to :name', { name: t('Missionaries') })}
      breadcrumbs={[{ title: t('Communication') }, { title: t('Emails'), href: route('communication.emails.index') }, { title: t('Missionaries') }]}
    >
      <EmailHeader name={t('Missionaries')} />

      <div className="mx-auto w-full max-w-2xl">
        <DataTable
          headerButton={
            <Button disabled={selectedMissionaries.length === 0} size="sm" onClick={handleNewEmail}>
              {t('New :model', { model: t('Email') })}
            </Button>
          }
          onSelectedRowsChange={setSelectedMissionaries}
          data={missionaries}
          rowId="id"
          columns={columns}
        />
      </div>
    </AppLayout>
  );
}
