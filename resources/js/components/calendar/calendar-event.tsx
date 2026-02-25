import { useCalendarContext } from '@/components/calendar/calendar-context';
import { cn } from '@/lib/utils';
import type { CalendarEvent as CalendarEventType } from '@/types/models/calendar-event';
import { format, isSameDay, isSameMonth } from 'date-fns';
import { AnimatePresence, motion, MotionConfig } from 'motion/react';

interface EventPosition {
  left: string;
  width: string;
  top: string;
  height: string;
}

function getOverlappingEvents(currentEvent: CalendarEventType, events: CalendarEventType[]): CalendarEventType[] {
  return events.filter((event) => {
    if (event.id === currentEvent.id) return false;
    return currentEvent.startAt < event.endAt && currentEvent.endAt > event.startAt && isSameDay(currentEvent.startAt, event.startAt);
  });
}

function calculateEventPosition(event: CalendarEventType, allEvents: CalendarEventType[]): EventPosition {
  const start = new Date(event.startAt);
  const end = new Date(event.endAt);
  const overlappingEvents = getOverlappingEvents(event, allEvents);
  const group = [event, ...overlappingEvents].sort((a, b) => new Date(a.startAt).getTime() - new Date(b.startAt).getTime());
  const position = group.indexOf(event);
  const width = `${100 / (overlappingEvents.length + 1)}%`;
  const left = `${(position * 100) / (overlappingEvents.length + 1)}%`;

  const startHour = start.getHours();
  const startMinutes = start.getMinutes();

  let endHour = end.getHours();
  let endMinutes = end.getMinutes();

  if (!isSameDay(start, end)) {
    endHour = 23;
    endMinutes = 59;
  }

  const topPosition = startHour * 128 + (startMinutes / 60) * 128;
  const duration = endHour * 60 + endMinutes - (startHour * 60 + startMinutes);
  const height = (duration / 60) * 128;

  return {
    left,
    width,
    top: `${topPosition}px`,
    height: `${height}px`,
  };
}

export default function CalendarEvent({ event, month = false, className }: { event: CalendarEventType; month?: boolean; className?: string }) {
  const { events, setSelectedEvent, setManageEventDialogOpen, date } = useCalendarContext();
  const style = month ? {} : calculateEventPosition(event, events);

  // Generate a unique key that includes the current month to prevent animation conflicts
  const isEventInCurrentMonth = isSameMonth(new Date(event.startAt), date);
  const animationKey = `${event.id}-${isEventInCurrentMonth ? 'current' : 'adjacent'}`;

  return (
    <MotionConfig reducedMotion="user">
      <AnimatePresence mode="wait">
        <motion.div
          className={cn(
            `cursor-pointer truncate rounded-md px-3 py-1.5 transition-all duration-300 bg-${event.color}-500/10 hover:bg-${event.color}-500/20 border border-${event.color}-500`,
            !month && 'absolute',
            className,
          )}
          style={style}
          onClick={(e) => {
            e.stopPropagation();
            setSelectedEvent(event);
            setManageEventDialogOpen(true);
          }}
          initial={{
            opacity: 0,
            y: -3,
            scale: 0.98,
          }}
          animate={{
            opacity: 1,
            y: 0,
            scale: 1,
          }}
          exit={{
            opacity: 0,
            scale: 0.98,
            transition: {
              duration: 0.15,
              ease: 'easeOut',
            },
          }}
          transition={{
            duration: 0.2,
            ease: [0.25, 0.1, 0.25, 1],
            opacity: {
              duration: 0.2,
              ease: 'linear',
            },
            layout: {
              duration: 0.2,
              ease: 'easeOut',
            },
          }}
          layoutId={`event-${animationKey}-${month ? 'month' : 'day'}`}
        >
          <motion.div
            className={cn(`flex w-full flex-col text-${event.color}-500`, month && 'flex-row items-center justify-between')}
            layout="position"
          >
            <p className={cn('truncate font-bold', month && 'text-xs')}>{event.title}</p>
            <p className={cn('text-sm', month && 'text-xs')}>
              <span>{format(new Date(event.startAt), 'h:mm a')}</span>
              <span className={cn('mx-1', month && 'hidden')}>-</span>
              <span className={cn(month && 'hidden')}>{format(new Date(event.endAt), 'h:mm a')}</span>
            </p>
          </motion.div>
        </motion.div>
      </AnimatePresence>
    </MotionConfig>
  );
}
