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
import type { CalendarEvent } from '@/types/models/calendar-event';
import { usePage } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { useMemo } from 'react';

interface CalendarEventsReportProps {
  events: CalendarEvent[];
  columns: PdfColumn[];
}

export default function CalendarEventsReport({ events, columns }: CalendarEventsReportProps) {
  const { t } = useTranslations();
  const dataColumns = useMemo<ColumnDef<CalendarEvent>[]>(
    () => [
      selectionHeader as ColumnDef<CalendarEvent>,
      {
        enableHiding: false,
        accessorKey: 'title',
        header: ({ column }) => <DataTableColumnHeader column={column} title={'Event title'} />,
        cell: ({ row }) => row.original.title,
      },
      {
        enableHiding: false,
        accessorKey: 'location',
        header: ({ column }) => <DataTableColumnHeader column={column} title={'Location'} />,
        cell: ({ row }) => row.original.location || '-',
      },
    ],
    [],
  );

  return (
    <AppLayout
      title={t(':model report', { model: t('Calendar Events') })}
      breadcrumbs={[
        { title: t('Calendar Events'), href: route('calendar-events.index') },
        { title: t(':model report', { model: t('Calendar Events') }) },
      ]}
    >
      <PageTitle>{t(':model report', { model: t('Calendar Events') })}</PageTitle>
      <FormErrorList errors={usePage().props.errors} />
      <PdfGeneratorProvider columns={columns} route="calendar-events.pdf.show">
        <OpenPdfButton />
        <section className="grid h-100 grid-cols-1 gap-4 md:grid-cols-2">
          <PdfControls />
          <PdfPreview />
        </section>
        <PdfRowsTable data={events} columns={dataColumns} />
      </PdfGeneratorProvider>
    </AppLayout>
  );
}
