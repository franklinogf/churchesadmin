import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';

import { selectionHeader } from '@/components/custom-ui/datatable/columns';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { ModelMorphName, SessionName } from '@/enums';
import type { Missionary } from '@/types/models/missionary';
import { router } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { useMemo, useState } from 'react';
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
    ],
    [t],
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
      title={t('Send email to members')}
      breadcrumbs={[{ title: t('Communication') }, { title: t('Emails'), href: route('communication.emails.index') }, { title: t('Missionaries') }]}
    >
      <header className="flex flex-col items-center gap-2">
        <PageTitle description={t('Select the missionaries you want to send a message to')}>{t('Send email to missionaries')}</PageTitle>
        <small className="text-muted-foreground text-xs">{t('Only missionaries with an email address will be shown in the list.')}</small>
      </header>

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
