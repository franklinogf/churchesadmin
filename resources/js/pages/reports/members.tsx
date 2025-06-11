import { selectionHeader } from '@/components/custom-ui/datatable/columns';
import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { FormErrorList } from '@/components/forms/form-error-list';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { useTranslations } from '@/hooks/use-translations';
import { usePdfGenerator } from '@/hooks/usePdfGenerator';
import AppLayout from '@/layouts/app-layout';
import type { PdfColumn } from '@/types';
import type { Member } from '@/types/models/member';
import { usePage } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { useMemo } from 'react';

interface MembersReportProps {
  members: Member[];
  columns: PdfColumn[];
}

export default function MembersReport({ members, columns }: MembersReportProps) {
  const { t } = useTranslations();
  const { PdfOptionsSelection, PdfPreview, onRowsChange, isLoading, routeSrc } = usePdfGenerator({
    columns,
    route: 'reports.members.pdf',
  });

  const dataColumns = useMemo<ColumnDef<Member>[]>(
    () => [
      selectionHeader as ColumnDef<Member>,
      {
        enableHiding: false,
        accessorKey: 'name',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Name" />,
        cell: ({ row }) => `${row.original.name} ${row.original.lastName}`,
      },
    ],
    [],
  );

  const openPdfInNewTab = () => {
    window.open(routeSrc);
  };

  return (
    <AppLayout
      title={t(':model report', { model: t('Members') })}
      breadcrumbs={[{ title: t('Reports'), href: route('reports') }, { title: t(':model report', { model: t('Members') }) }]}
    >
      <PageTitle>{t(':model report', { model: t('Members') })}</PageTitle>
      <FormErrorList errors={usePage().props.errors} />
      <div className="mb-1 flex justify-end">
        <Button disabled={isLoading} size="sm" onClick={openPdfInNewTab}>
          {t('Open in new tab')}
        </Button>
      </div>
      <section className="grid h-[400px] grid-cols-1 gap-4 md:grid-cols-2">
        <PdfOptionsSelection />
        <PdfPreview />
      </section>
      <DataTable onSelectedRowsChange={onRowsChange} columns={dataColumns} rowId="id" data={members} />
    </AppLayout>
  );
}
