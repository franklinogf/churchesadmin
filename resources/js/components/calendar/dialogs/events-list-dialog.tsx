import { useCalendar } from '@/components/calendar/contexts/calendar-context';
import { EventDetailsDialog } from '@/components/calendar/dialogs/event-details-dialog';
import { formatTime } from '@/components/calendar/helpers';
import type { IEvent } from '@/components/calendar/interfaces';
import { dayCellVariants } from '@/components/calendar/views/month-view/day-cell';
import { EventBullet } from '@/components/calendar/views/month-view/event-bullet';
import { Modal, ModalContent, ModalHeader, ModalTitle, ModalTrigger } from '@/components/ui/responsive-modal';
import { cn } from '@/lib/utils';
import { format } from 'date-fns';
import type { ReactNode } from 'react';

interface EventListDialogProps {
  date: Date;
  events: IEvent[];
  maxVisibleEvents?: number;
  children?: ReactNode;
}

export function EventListDialog({ date, events, maxVisibleEvents = 3, children }: EventListDialogProps) {
  const cellEvents = events;
  const hiddenEventsCount = Math.max(cellEvents.length - maxVisibleEvents, 0);
  const { badgeVariant, use24HourFormat } = useCalendar();

  const defaultTrigger = (
    <span className="cursor-pointer">
      <span className="sm:hidden">+{hiddenEventsCount}</span>
      <span className="my-1 hidden rounded-xl border px-2 py-0.5 sm:inline">
        {hiddenEventsCount}
        <span className="mx-1">more...</span>
      </span>
    </span>
  );

  return (
    <Modal>
      <ModalTrigger asChild>{children || defaultTrigger}</ModalTrigger>
      <ModalContent className="sm:max-w-106.25">
        <ModalHeader>
          <ModalTitle className="my-2">
            <div className="flex items-center gap-2">
              <EventBullet color={cellEvents[0]!.color} className="" />
              <p className="text-sm font-medium">Events on {format(date, 'EEEE, MMMM d, yyyy')}</p>
            </div>
          </ModalTitle>
        </ModalHeader>
        <div className="max-h-[60vh] space-y-2 overflow-y-auto">
          {cellEvents.length > 0 ? (
            cellEvents.map((event) => (
              <EventDetailsDialog event={event} key={event.id}>
                <div
                  className={cn('hover:bg-muted flex cursor-pointer items-center gap-2 rounded-md border p-2', {
                    [dayCellVariants({ color: event.color })]: badgeVariant === 'colored',
                  })}
                >
                  <EventBullet color={event.color} />
                  <div className="flex w-full items-center justify-between">
                    <p className="text-sm font-medium">{event.title}</p>
                    <p className="text-xs">{formatTime(event.startAt, use24HourFormat)}</p>
                  </div>
                </div>
              </EventDetailsDialog>
            ))
          ) : (
            <p className="text-muted-foreground text-sm">No events for this date.</p>
          )}
        </div>
      </ModalContent>
    </Modal>
  );
}
