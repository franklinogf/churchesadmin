import { Card } from '@/components/ui/card';
import { formatEventTime } from '@/lib/calendar-utils';
import { cn } from '@/lib/utils';
import type { CalendarEvent } from '@/types/models/calendar-event';
import { format } from 'date-fns';
import { ClockIcon, MapPinIcon } from 'lucide-react';

interface CalendarEventCardProps {
  event: CalendarEvent;
  onClick?: () => void;
  viewType: 'month' | 'week' | 'day';
  className?: string;
}

export function CalendarEventCard({ event, onClick, viewType, className }: CalendarEventCardProps) {
  const isCompact = viewType === 'month';
  const isWeek = viewType === 'week';

  if (isWeek) {
    const start = new Date(event.startAt);
    const timeStr = format(start, 'h:mm a');

    return (
      <div
        className={cn('border-l-brand group bg-card cursor-pointer rounded border-l-4 px-1.5 py-1 transition-all hover:shadow-md', className)}
        onClick={(e) => {
          e.stopPropagation();
          onClick?.();
        }}
      >
        <p className="line-clamp-1 text-xs leading-tight font-semibold">{event.title}</p>
        <p className="text-muted-foreground text-[10px] leading-tight">{timeStr}</p>
      </div>
    );
  }

  return (
    <Card
      className={cn('border-l-brand cursor-pointer border-l-4 p-2 transition-shadow hover:shadow-md', isCompact ? 'text-xs' : 'text-sm', className)}
      onClick={(e) => {
        e.stopPropagation();
        onClick?.();
      }}
    >
      <div className="space-y-1">
        <p className={cn('line-clamp-1 font-semibold', isCompact ? 'text-xs' : 'text-sm')}>{event.title}</p>

        {!isCompact && (
          <>
            <div className="text-muted-foreground flex items-center gap-1">
              <ClockIcon className="size-3" />
              <span className="text-xs">{formatEventTime(event)}</span>
            </div>

            {event.location && (
              <div className="text-muted-foreground flex items-center gap-1">
                <MapPinIcon className="size-3" />
                <span className="line-clamp-1 text-xs">{event.location}</span>
              </div>
            )}

            {event.description && viewType === 'day' && <p className="text-muted-foreground line-clamp-2 text-xs">{event.description}</p>}
          </>
        )}
      </div>
    </Card>
  );
}
