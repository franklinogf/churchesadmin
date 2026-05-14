import Calendar from '@/components/calendar/calendar';
import type { Mode } from '@/components/calendar/calendar-types';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import type { CalendarEvent } from '@/types/models/calendar-event';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';

interface CalendarEventsIndexProps {
  events: CalendarEvent[];
}

export default function CalendarEventsIndex({ events: initialEvents }: CalendarEventsIndexProps) {
  const { t: tPages } = useTranslation('pages');
  const [events, setEvents] = useState<CalendarEvent[]>(initialEvents);
  const [mode, setMode] = useState<Mode>('month');
  const [date, setDate] = useState<Date>(new Date());

  return (
    <AppLayout title={tPages(($) => $.main.calendar.index.calendar)} breadcrumbs={[{ title: tPages(($) => $.main.calendar.index.calendar) }]}>
      <PageTitle description={tPages(($) => $.main.calendar.index.manageEventsAndActivities)}>
        {tPages(($) => $.main.calendar.index.calendar)}
      </PageTitle>
      <Calendar events={events} setEvents={setEvents} mode={mode} setMode={setMode} date={date} setDate={setDate} />
    </AppLayout>
  );
}
