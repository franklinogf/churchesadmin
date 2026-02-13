import Calendar from '@/components/calendar/calendar';
import type { Mode } from '@/components/calendar/calendar-types';
import { PageTitle } from '@/components/PageTitle';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import type { CalendarEvent } from '@/types/models/calendar-event';
import { useState } from 'react';

interface CalendarEventsIndexProps {
  events: CalendarEvent[];
}

export default function CalendarEventsIndex({ events: initialEvents }: CalendarEventsIndexProps) {
  const { t } = useTranslations();
  const [events, setEvents] = useState<CalendarEvent[]>(initialEvents);
  const [mode, setMode] = useState<Mode>('month');
  const [date, setDate] = useState<Date>(new Date());

  return (
    <AppLayout title={t('Calendar')} breadcrumbs={[{ title: t('Calendar') }]}>
      <PageTitle description={t('Manage events and activities')}>{t('Calendar')}</PageTitle>
      <Calendar events={events} setEvents={setEvents} mode={mode} setMode={setMode} date={date} setDate={setDate} />
    </AppLayout>
  );
}
