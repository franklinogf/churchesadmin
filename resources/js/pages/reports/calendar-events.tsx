import CalendarEventController from '@/actions/App/Http/Controllers/CalendarEventController';
import CalendarEventPdfController from '@/actions/App/Http/Controllers/Pdf/CalendarEventPdfController';
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
import type { CalendarEvent } from '@/types/models/calendar-event';
import { usePage } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { useMemo } from 'react';
import { useTranslation } from 'react-i18next';

interface CalendarEventsReportProps {
  events: CalendarEvent[];
  columns: PdfColumn[];
}

interface CalendarEventsReportContentProps {
  events: CalendarEvent[];
  dataColumns: ColumnDef<CalendarEvent>[];
}

function CalendarEventsReportContent({ events, dataColumns }: CalendarEventsReportContentProps) {
  const { routeSrc } = usePdfGenerator();

  return (
    <>
      <OpenPdfButton route={CalendarEventPdfController.show({ query: { ...routeSrc } }).url} />
      <section className="grid h-100 grid-cols-1 gap-4 md:grid-cols-2">
        <PdfControls />
        <PdfPreview route={CalendarEventPdfController.show({ query: { ...routeSrc } }).url} />
      </section>
      <PdfRowsTable data={events} columns={dataColumns} />
    </>
  );
}

export default function CalendarEventsReport({ events, columns }: CalendarEventsReportProps) {
  const { t: tPages } = useTranslation('pages');
  const dataColumns = useMemo<ColumnDef<CalendarEvent>[]>(
    () => [
      selectionHeader as ColumnDef<CalendarEvent>,
      {
        enableHiding: false,
        accessorKey: 'title',
        header: ({ column }) => <DatatableHeader column={column} title={'Event title'} />,
        cell: ({ row }) => row.original.title,
      },
      {
        enableHiding: false,
        accessorKey: 'location',
        header: ({ column }) => <DatatableHeader column={column} title={'Location'} />,
        cell: ({ row }) => row.original.location || '-',
      },
    ],
    [],
  );

  return (
    <AppLayout
      title={tPages(($) => $.reports.calendarEvents.modelReport, { model: tPages(($) => $.reports.calendarEvents.calendarEvents) })}
      breadcrumbs={[
        { title: tPages(($) => $.reports.calendarEvents.calendarEvents), href: CalendarEventController.index().url },
        { title: tPages(($) => $.reports.calendarEvents.modelReport, { model: tPages(($) => $.reports.calendarEvents.calendarEvents) }) },
      ]}
    >
      <PageTitle>{tPages(($) => $.reports.calendarEvents.modelReport, { model: tPages(($) => $.reports.calendarEvents.calendarEvents) })}</PageTitle>
      <FormErrorList errors={usePage().props.errors} />
      <PdfGeneratorProvider columns={columns} route={CalendarEventPdfController.index().url}>
        <CalendarEventsReportContent events={events} dataColumns={dataColumns} />
      </PdfGeneratorProvider>
    </AppLayout>
  );
}
