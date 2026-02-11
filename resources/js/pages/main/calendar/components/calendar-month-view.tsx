import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useTranslations } from '@/hooks/use-translations';
import { addEventsToDays, getMonthDays } from '@/lib/calendar-utils';
import { cn } from '@/lib/utils';
import type { CalendarEvent } from '@/types/models/calendar-event';
import { format } from 'date-fns';
import { CalendarEventCard } from './calendar-event-card';

interface CalendarMonthViewProps {
  currentDate: Date;
  events: CalendarEvent[];
  onDateClick: (date: Date) => void;
  onEventClick: (event: CalendarEvent) => void;
}

export function CalendarMonthView({ currentDate, events, onDateClick, onEventClick }: CalendarMonthViewProps) {
  const { t } = useTranslations();
  const days = addEventsToDays(getMonthDays(currentDate), events);
  const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

  const MAX_VISIBLE_EVENTS = 3;

  return (
    <div className="flex flex-col rounded-lg border">
      {/* Weekday headers */}
      <div className="bg-muted/50 grid grid-cols-7 border-b">
        {weekDays.map((day) => (
          <div key={day} className="border-r p-2 text-center text-sm font-semibold last:border-r-0">
            {day}
          </div>
        ))}
      </div>

      {/* Calendar grid */}
      <div className="grid grid-cols-7">
        {days.map((day, index) => {
          const visibleEvents = day.events.slice(0, MAX_VISIBLE_EVENTS);
          const hiddenCount = day.events.length - MAX_VISIBLE_EVENTS;

          return (
            <div
              key={index}
              className={cn(
                'group relative min-h-32 border-r border-b p-2 last:border-r-0',
                !day.isCurrentMonth && 'bg-muted/30',
                day.isToday && 'bg-brand/5',
              )}
            >
              {/* Date number */}
              <div className="mb-2 flex items-center justify-between">
                <Button
                  variant="ghost"
                  size="sm"
                  onClick={() => onDateClick(day.date)}
                  className={cn(
                    'h-6 w-6 cursor-pointer rounded-full p-0 text-xs',
                    day.isToday && 'bg-brand text-brand-foreground hover:bg-brand/90',
                    !day.isCurrentMonth && 'text-muted-foreground',
                  )}
                >
                  {format(day.date, 'd')}
                </Button>
              </div>

              {/* Events */}
              <div className="space-y-1">
                {visibleEvents.map((event) => (
                  <CalendarEventCard
                    key={event.id}
                    event={event}
                    viewType="month"
                    onClick={() => {
                      onEventClick(event);
                    }}
                  />
                ))}

                {hiddenCount > 0 && (
                  <Badge variant="outline" className="w-full justify-center text-[10px]">
                    +{hiddenCount} more
                  </Badge>
                )}
              </div>

              {/* Empty state - show on hover */}
              {day.events.length === 0 && (
                <button
                  onClick={() => onDateClick(day.date)}
                  className="hover:bg-accent/50 absolute inset-0 flex items-center justify-center opacity-0 transition-opacity hover:opacity-100"
                >
                  <span className="text-muted-foreground text-xs">+ {t('Add event')}</span>
                </button>
              )}
            </div>
          );
        })}
      </div>
    </div>
  );
}
