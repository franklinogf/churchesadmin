import { CalendarBody } from '@/components/calendar/calendar-body';
import { CalendarProvider } from '@/components/calendar/contexts/calendar-context';
import { DndProvider } from '@/components/calendar/contexts/dnd-context';
import { CalendarHeader } from '@/components/calendar/header/calendar-header';
import type { IEvent, IUser } from './interfaces';

interface ICalendarData {
  events: IEvent[];
  users?: IUser[];
}

export function Calendar({ events, users }: ICalendarData) {
  return (
    <CalendarProvider events={events} users={users} view="month">
      <DndProvider>
        <div className="w-full rounded-lg border">
          <CalendarHeader />
          <CalendarBody />
        </div>
      </DndProvider>
    </CalendarProvider>
  );
}
