import { Button } from '@/components/ui/button';
import { calculateEventLayout, calculateEventPosition, getEventsForDate, getHourSlots } from '@/lib/calendar-utils';
import { cn } from '@/lib/utils';
import type { CalendarEvent } from '@/types/models/calendar-event';
import { format, isToday as isTodayFns } from 'date-fns';
import { CalendarEventCard } from './calendar-event-card';

interface CalendarDayViewProps {
  currentDate: Date;
  events: CalendarEvent[];
  onTimeSlotClick: (date: Date, hour: number) => void;
  onEventClick: (event: CalendarEvent) => void;
}

export function CalendarDayView({ currentDate, events, onTimeSlotClick, onEventClick }: CalendarDayViewProps) {
  const dayEvents = getEventsForDate(events, currentDate).toReversed();
  const hours = getHourSlots();
  const HOUR_HEIGHT = 60; // px
  const isToday = isTodayFns(currentDate);
  const eventLayout = calculateEventLayout(dayEvents);

  return (
    <div className="flex flex-col rounded-lg border">
      {/* Header */}
      <div className={cn('bg-muted/50 border-b p-4 text-center', isToday && 'bg-brand/10')}>
        <div className="text-muted-foreground text-sm font-semibold">{format(currentDate, 'EEEE')}</div>
        <div className={cn('text-3xl font-bold', isToday && 'text-brand')}>{format(currentDate, 'd')}</div>
        <div className="text-muted-foreground text-sm">{format(currentDate, 'MMMM yyyy')}</div>
      </div>

      {/* Time grid */}
      <div className="relative overflow-auto" style={{ maxHeight: '600px' }}>
        <div className="flex">
          {/* Hour labels */}
          <div className="bg-background sticky left-0 z-10 w-20">
            {hours.map((hour) => (
              <div key={hour} className="border-r border-b p-2 text-right" style={{ height: `${HOUR_HEIGHT}px` }}>
                <span className="text-muted-foreground text-xs">{format(new Date().setHours(hour, 0), 'h a')}</span>
              </div>
            ))}
          </div>

          {/* Time slots */}
          <div className="relative flex-1">
            {hours.map((hour) => (
              <Button
                variant="ghost"
                key={hour}
                onClick={() => onTimeSlotClick(currentDate, hour)}
                className={cn('hover:bg-accent/50 w-full rounded-none border-b transition-colors', isToday && 'bg-primary/5')}
                style={{ height: `${HOUR_HEIGHT}px` }}
              />
            ))}

            {/* Positioned timed events */}
            <div className="pointer-events-none absolute inset-0 px-2">
              {dayEvents.map((event, i) => {
                const { top, height } = calculateEventPosition(event);
                const layout = eventLayout.get(i) || { column: 0, totalColumns: 1 };
                const width = `${100 / layout.totalColumns}%`;
                const left = `${(layout.column * 100) / layout.totalColumns}%`;

                return (
                  <div
                    key={event.id}
                    className="group pointer-events-auto absolute flex overflow-hidden transition-all duration-200 hover:scale-[1.02] hover:shadow-lg"
                    style={{
                      zIndex: i + 10,
                      left,
                      width,
                      top: `${top}px`,
                      height: `${height}px`,
                    }}
                    onMouseEnter={(e) => (e.currentTarget.style.zIndex = '999')}
                    onMouseLeave={(e) => (e.currentTarget.style.zIndex = `${i + 10}`)}
                  >
                    <CalendarEventCard event={event} viewType="day" onClick={() => onEventClick(event)} className="h-full w-full" />
                  </div>
                );
              })}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
