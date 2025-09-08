import { selectionHeader } from '@/components/custom-ui/datatable/columns';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { FormErrorList } from '@/components/forms/form-error-list';
import { PageTitle } from '@/components/PageTitle';
import { PdfGeneratorProvider } from '@/contexts/pdf-generator-context';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import { OpenPdfButton } from '@/pages/reports/components/open-pdf-button';
import { PdfControls } from '@/pages/reports/components/pdf-controls';
import { PdfPreview } from '@/pages/reports/components/pdf-preview';
import { PdfRowsTable } from '@/pages/reports/components/pdf-rows-table';
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

  return (
    <AppLayout
      title={t(':model report', { model: t('Members') })}
      breadcrumbs={[{ title: t('Reports'), href: route('reports') }, { title: t(':model report', { model: t('Members') }) }]}
    >
      <PageTitle>{t(':model report', { model: t('Members') })}</PageTitle>
      <FormErrorList errors={usePage().props.errors} />
      <PdfGeneratorProvider columns={columns} route="reports.members.pdf">
        <OpenPdfButton />
        <section className="grid h-[400px] grid-cols-1 gap-4 md:grid-cols-2">
          <PdfControls />
          <PdfPreview />
        </section>
        <PdfRowsTable data={members} columns={dataColumns} />
      </PdfGeneratorProvider>
    </AppLayout>
  );
}
