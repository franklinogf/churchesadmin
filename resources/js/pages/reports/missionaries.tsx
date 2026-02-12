import MissionaryPdfController from '@/actions/App/Http/Controllers/Pdf/MissionaryPdfController';
import ReportController from '@/actions/App/Http/Controllers/ReportController';
import { selectionHeader } from '@/components/custom-ui/datatable/columns';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { FormErrorList } from '@/components/forms/form-error-list';
import { PageTitle } from '@/components/PageTitle';
import { PdfGeneratorProvider, usePdfGenerator } from '@/contexts/pdf-generator-context';
import { useTranslations } from '@/hooks/use-translations';
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

interface MissionariesReportProps {
  missionaries: Missionary[];
  columns: PdfColumn[];
}

export default function MissionariesReport({ missionaries, columns }: MissionariesReportProps) {
  const { t } = useTranslations();
  const { routeSrc } = usePdfGenerator();
  const dataColumns = useMemo<ColumnDef<Missionary>[]>(
    () => [
      selectionHeader as ColumnDef<Missionary>,
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
      title={t(':model report', { model: t('Missionaries') })}
      breadcrumbs={[{ title: t('Reports'), href: ReportController().url }, { title: t(':model report', { model: t('Missionaries') }) }]}
    >
      <PageTitle>{t(':model report', { model: t('Missionaries') })}</PageTitle>
      <FormErrorList errors={usePage().props.errors} />
      <PdfGeneratorProvider columns={columns} route="reports.missionaries.pdf">
        <OpenPdfButton route={MissionaryPdfController.show({ query: { ...routeSrc } }).url} />
        <section className="grid h-100 grid-cols-1 gap-4 md:grid-cols-2">
          <PdfControls />
          <PdfPreview route={MissionaryPdfController.show({ query: { ...routeSrc } }).url} />
        </section>
        <PdfRowsTable data={missionaries} columns={dataColumns} />
      </PdfGeneratorProvider>
    </AppLayout>
  );
}
