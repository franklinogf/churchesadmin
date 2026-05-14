import MemberPdfController from '@/actions/App/Http/Controllers/Pdf/MemberPdfController';
import ReportController from '@/actions/App/Http/Controllers/ReportController';
import { selectionHeader } from '@/components/datatable/columns';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { FormErrorList } from '@/components/forms/form-error-list';
import { PageTitle } from '@/components/PageTitle';
import { PdfGeneratorProvider, usePdfGenerator } from '@/contexts/pdf-generator-context';
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
import { useTranslation } from 'react-i18next';

interface MembersReportProps {
  members: Member[];
  columns: PdfColumn[];
}

interface MembersReportContentProps {
  members: Member[];
  dataColumns: ColumnDef<Member>[];
}

function MembersReportContent({ members, dataColumns }: MembersReportContentProps) {
  const { routeSrc } = usePdfGenerator();

  return (
    <>
      <OpenPdfButton route={MemberPdfController.show({ query: { ...routeSrc } }).url} />
      <section className="grid h-100 grid-cols-1 gap-4 md:grid-cols-2">
        <PdfControls />
        <PdfPreview route={MemberPdfController.show({ query: { ...routeSrc } }).url} />
      </section>
      <PdfRowsTable data={members} columns={dataColumns} />
    </>
  );
}

export default function MembersReport({ members, columns }: MembersReportProps) {
  const { t: tPages } = useTranslation('pages');
  const dataColumns = useMemo<ColumnDef<Member>[]>(
    () => [
      selectionHeader as ColumnDef<Member>,
      {
        enableHiding: false,
        accessorKey: 'name',
        header: ({ column }) => <DatatableHeader column={column} title="Name" />,
        cell: ({ row }) => `${row.original.name} ${row.original.lastName}`,
      },
    ],
    [],
  );

  return (
    <AppLayout
      title={tPages(($) => $.reports.members.modelReport, { model: tPages(($) => $.reports.members.members) })}
      breadcrumbs={[
        { title: tPages(($) => $.reports.members.reports), href: ReportController().url },
        { title: tPages(($) => $.reports.members.modelReport, { model: tPages(($) => $.reports.members.members) }) },
      ]}
    >
      <PageTitle>{tPages(($) => $.reports.members.modelReport, { model: tPages(($) => $.reports.members.members) })}</PageTitle>
      <FormErrorList errors={usePage().props.errors} />
      <PdfGeneratorProvider columns={columns} route="reports.members.pdf">
        <MembersReportContent members={members} dataColumns={dataColumns} />
      </PdfGeneratorProvider>
    </AppLayout>
  );
}
