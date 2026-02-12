'use client';

import { useCalendar } from '@/components/calendar/contexts/calendar-context';
import { AddEditEventDialog } from '@/components/calendar/dialogs/add-edit-event-dialog';
import { formatTime } from '@/components/calendar/helpers';
import type { IEvent } from '@/components/calendar/interfaces';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { ScrollArea } from '@/components/ui/scroll-area';
import { format, parseISO } from 'date-fns';
import { Calendar, Clock, Text, User } from 'lucide-react';
import type { ReactNode } from 'react';
import { toast } from 'sonner';

interface IProps {
  event: IEvent;
  children: ReactNode;
}

export function EventDetailsDialog({ event, children }: IProps) {
  const startDate = parseISO(event.startAt);
  const endDate = parseISO(event.endAt);
  const { use24HourFormat, removeEvent } = useCalendar();

  const deleteEvent = (eventId: number) => {
    try {
      removeEvent(eventId);
      toast.success('Event deleted successfully.');
    } catch {
      toast.error('Error deleting event.');
    }
  };

  return (
    <Dialog>
      <DialogTrigger asChild>{children}</DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>{event.title}</DialogTitle>
        </DialogHeader>

        <ScrollArea className="max-h-[80vh]">
          <div className="space-y-4 p-4">
            <div className="flex items-start gap-2">
              <User className="text-muted-foreground mt-1 size-4 shrink-0" />
              <div>
                <p className="text-sm font-medium">Responsible</p>
                <p className="text-muted-foreground text-sm">{event.creator?.name}</p>
              </div>
            </div>

            <div className="flex items-start gap-2">
              <Calendar className="text-muted-foreground mt-1 size-4 shrink-0" />
              <div>
                <p className="text-sm font-medium">Start Date</p>
                <p className="text-muted-foreground text-sm">
                  {format(startDate, 'EEEE dd MMMM')}
                  <span className="mx-1">at</span>
                  {formatTime(parseISO(event.startAt), use24HourFormat)}
                </p>
              </div>
            </div>

            <div className="flex items-start gap-2">
              <Clock className="text-muted-foreground mt-1 size-4 shrink-0" />
              <div>
                <p className="text-sm font-medium">End Date</p>
                <p className="text-muted-foreground text-sm">
                  {format(endDate, 'EEEE dd MMMM')}
                  <span className="mx-1">at</span>
                  {formatTime(parseISO(event.endAt), use24HourFormat)}
                </p>
              </div>
            </div>

            <div className="flex items-start gap-2">
              <Text className="text-muted-foreground mt-1 size-4 shrink-0" />
              <div>
                <p className="text-sm font-medium">Description</p>
                <p className="text-muted-foreground text-sm">{event.description}</p>
              </div>
            </div>
          </div>
        </ScrollArea>
        <div className="flex justify-end gap-2">
          <AddEditEventDialog event={event}>
            <Button variant="outline">Edit</Button>
          </AddEditEventDialog>
          <Button
            variant="destructive"
            onClick={() => {
              deleteEvent(event.id);
            }}
          >
            Delete
          </Button>
        </div>
        <DialogClose />
      </DialogContent>
    </Dialog>
  );
}
