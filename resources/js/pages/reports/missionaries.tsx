import MissionaryPdfController from '@/actions/App/Http/Controllers/Pdf/MissionaryPdfController';
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
import type { Missionary } from '@/types/models/missionary';
import { usePage } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { useMemo } from 'react';
import { useTranslation } from 'react-i18next';

interface MissionariesReportProps {
  missionaries: Missionary[];
  columns: PdfColumn[];
}

interface MissionariesReportContentProps {
  missionaries: Missionary[];
  dataColumns: ColumnDef<Missionary>[];
}

function MissionariesReportContent({ missionaries, dataColumns }: MissionariesReportContentProps) {
  const { routeSrc } = usePdfGenerator();

  return (
    <>
      <OpenPdfButton route={MissionaryPdfController.show({ query: { ...routeSrc } }).url} />
      <section className="grid h-100 grid-cols-1 gap-4 md:grid-cols-2">
        <PdfControls />
        <PdfPreview route={MissionaryPdfController.show({ query: { ...routeSrc } }).url} />
      </section>
      <PdfRowsTable data={missionaries} columns={dataColumns} />
    </>
  );
}

export default function MissionariesReport({ missionaries, columns }: MissionariesReportProps) {
  const { t: tPages } = useTranslation('pages');
  const dataColumns = useMemo<ColumnDef<Missionary>[]>(
    () => [
      selectionHeader as ColumnDef<Missionary>,
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
      title={tPages(($) => $.reports.missionaries.modelReport, { model: tPages(($) => $.reports.missionaries.missionaries) })}
      breadcrumbs={[
        { title: tPages(($) => $.reports.missionaries.reports), href: ReportController().url },
        { title: tPages(($) => $.reports.missionaries.modelReport, { model: tPages(($) => $.reports.missionaries.missionaries) }) },
      ]}
    >
      <PageTitle>{tPages(($) => $.reports.missionaries.modelReport, { model: tPages(($) => $.reports.missionaries.missionaries) })}</PageTitle>
      <FormErrorList errors={usePage().props.errors} />
      <PdfGeneratorProvider columns={columns} route="reports.missionaries.pdf">
        <MissionariesReportContent missionaries={missionaries} dataColumns={dataColumns} />
      </PdfGeneratorProvider>
    </AppLayout>
  );
}
