import { addEventsToDays, calculateEventLayout, calculateEventPosition, getHourSlots, getWeekDays } from '@/lib/calendar-utils';
import { cn } from '@/lib/utils';
import type { CalendarEvent } from '@/types/models/calendar-event';
import { format } from 'date-fns';
import { CalendarEventCard } from './calendar-event-card';

interface CalendarWeekViewProps {
  currentDate: Date;
  events: CalendarEvent[];
  onTimeSlotClick: (date: Date, hour: number) => void;
  onEventClick: (event: CalendarEvent) => void;
  onHeaderClick: (date: Date) => void;
}

export function CalendarWeekView({ currentDate, events, onTimeSlotClick, onEventClick, onHeaderClick }: CalendarWeekViewProps) {
  const days = addEventsToDays(getWeekDays(currentDate), events);
  const hours = getHourSlots();
  const HOUR_HEIGHT = 60; // px
  return (
    <div className="flex flex-col rounded-lg border">
      {/* Header row with day names */}
      <div className="bg-muted/50 grid grid-cols-[80px_repeat(7,minmax(83.89px,200px))] border-b">
        <div className="border-r p-2"></div>
        {days.map((day, index) => (
          <div key={index} className={cn('border-r p-2 text-center last:border-r-0', day.isToday && 'bg-brand/10')}>
            <button onClick={() => onHeaderClick(day.date)} className="mx-auto flex w-full cursor-pointer flex-col select-none">
              <div className="text-sm font-semibold">{format(day.date, 'EEE')}</div>
              <div className={cn('text-lg font-bold', day.isToday && 'text-brand')}>{format(day.date, 'd')}</div>
            </button>
          </div>
        ))}
      </div>

      {/* Time grid */}
      <div className="relative overflow-auto" style={{ maxHeight: '600px' }}>
        <div className="grid grid-cols-[80px_repeat(7,minmax(83.89px,200px))]">
          {/* Hour labels and time slots */}
          {hours.map((hour) => (
            <div key={hour} className="contents">
              {/* Hour label */}
              <div className="bg-background sticky left-0 z-10 border-r border-b p-2" style={{ height: `${HOUR_HEIGHT}px` }}>
                <span className="text-muted-foreground text-xs select-none">{format(new Date().setHours(hour, 0), 'h a')}</span>
              </div>

              {/* Time slots for each day */}
              {days.map((day, dayIndex) => (
                <button
                  key={dayIndex}
                  onClick={() => onTimeSlotClick(day.date, hour)}
                  className={cn('hover:bg-accent/50 relative border-r border-b transition-colors last:border-r-0', day.isToday && 'bg-brand/5')}
                  style={{ height: `${HOUR_HEIGHT}px` }}
                />
              ))}
            </div>
          ))}
        </div>

        {/* Positioned timed events */}
        <div className="pointer-events-none absolute inset-0">
          <div className="relative grid grid-cols-[80px_repeat(7,minmax(83.89px,200px))]">
            <div></div>
            {days.map((day, dayIndex) => {
              const eventLayout = calculateEventLayout(day.events);

              return (
                <div key={dayIndex} className="relative">
                  {day.events.map((event, eventIndex) => {
                    const { top, height } = calculateEventPosition(event);
                    const layout = eventLayout.get(eventIndex) || { column: 0, totalColumns: 1 };
                    const width = `calc((100% - 0.5rem) / ${layout.totalColumns})`;
                    const left = `calc(0.25rem + ((100% - 0.5rem) / ${layout.totalColumns}) * ${layout.column})`;

                    return (
                      <div
                        key={event.id}
                        className="pointer-events-auto absolute overflow-hidden"
                        style={{
                          top: `${top}px`,
                          height: `${height}px`,
                          left,
                          width,
                        }}
                      >
                        <CalendarEventCard event={event} viewType="week" onClick={() => onEventClick(event)} className="h-full" />
                      </div>
                    );
                  })}
                </div>
              );
            })}
          </div>
        </div>
      </div>
    </div>
  );
}
