'use client';

import { fadeIn, transition } from '@/components/calendar/animations';
import { useCalendar } from '@/components/calendar/contexts/calendar-context';
import { AgendaEvents } from '@/components/calendar/views/agenda-view/agenda-events';
import { CalendarMonthView } from '@/components/calendar/views/month-view/calendar-month-view';
import { CalendarDayView } from '@/components/calendar/views/week-and-day-view/calendar-day-view';
import { CalendarWeekView } from '@/components/calendar/views/week-and-day-view/calendar-week-view';
import { CalendarYearView } from '@/components/calendar/views/year-view/calendar-year-view';
import { isSameDay, parseISO } from 'date-fns';
import { motion } from 'framer-motion';

export function CalendarBody() {
  const { view, events } = useCalendar();

  const singleDayEvents = events.filter((event) => {
    const startDate = parseISO(event.startAt);
    const endDate = parseISO(event.endAt);
    return isSameDay(startDate, endDate);
  });

  const multiDayEvents = events.filter((event) => {
    const startDate = parseISO(event.startAt);
    const endDate = parseISO(event.endAt);
    return !isSameDay(startDate, endDate);
  });

  return (
    <div className="relative h-full w-full overflow-scroll">
      <motion.div key={view} initial="initial" animate="animate" exit="exit" variants={fadeIn} transition={transition}>
        {view === 'month' && <CalendarMonthView singleDayEvents={singleDayEvents} multiDayEvents={multiDayEvents} />}
        {view === 'week' && <CalendarWeekView singleDayEvents={singleDayEvents} multiDayEvents={multiDayEvents} />}
        {view === 'day' && <CalendarDayView singleDayEvents={singleDayEvents} multiDayEvents={multiDayEvents} />}
        {view === 'year' && <CalendarYearView singleDayEvents={singleDayEvents} multiDayEvents={multiDayEvents} />}
        {view === 'agenda' && (
          <motion.div key="agenda" initial="initial" animate="animate" exit="exit" variants={fadeIn} transition={transition}>
            <AgendaEvents />
          </motion.div>
        )}
      </motion.div>
    </div>
  );
}
