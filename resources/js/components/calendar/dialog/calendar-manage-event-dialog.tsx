import { Dialog, DialogClose, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { useCallback, useEffect, type SubmitEvent } from 'react';

import CalendarEventController from '@/actions/App/Http/Controllers/CalendarEventController';
import { ColorPicker } from '@/components/forms/inputs/color-picker';
import { DatetimeField } from '@/components/forms/inputs/DatetimeField';
import { InputField } from '@/components/forms/inputs/InputField';
import { TextareaField } from '@/components/forms/inputs/TextareaField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { Button } from '@/components/ui/button';
import { FieldGroup } from '@/components/ui/field';
import { CalendarEventColorEnum } from '@/enums/CalendarEventColorEnum';
import useConfirmationStore from '@/stores/confirmationStore';
import type { CalendarEvent } from '@/types/models/calendar-event';
import { useForm } from '@inertiajs/react';
import { useCalendarContext } from '../calendar-context';

interface CalendarForm {
  title: string;
  location: string;
  description: string;
  start_at: Date;
  end_at: Date;
  color: CalendarEventColorEnum;
}
export default function CalendarManageEventDialog() {
  const { manageEventDialogOpen, setManageEventDialogOpen, selectedEvent, setSelectedEvent, events, setEvents } = useCalendarContext();
  const { openConfirmation } = useConfirmationStore();
  const { data, setData, submit, processing } = useForm<CalendarForm>({
    title: '',
    start_at: selectedEvent ? new Date(selectedEvent.startAt) : new Date(),
    end_at: new Date(),
    color: CalendarEventColorEnum.BLUE,
    location: '',
    description: '',
  });

  useEffect(() => {
    if (!selectedEvent) return;
    setData({
      title: selectedEvent.title,
      start_at: new Date(selectedEvent.startAt),
      end_at: new Date(selectedEvent.endAt),
      color: selectedEvent.color,
      location: selectedEvent.location || '',
      description: selectedEvent.description || '',
    });
  }, [selectedEvent, setData]);

  function handleSubmit(e: SubmitEvent<HTMLFormElement>) {
    e.preventDefault();
    if (!selectedEvent) return;

    submit(CalendarEventController.update(selectedEvent.id), {
      onSuccess: () => {
        const updatedEvent: CalendarEvent = {
          ...selectedEvent,
          title: data.title,
          startAt: new Date(data.start_at).toISOString(),
          endAt: new Date(data.end_at).toISOString(),
          color: data.color,
          location: data.location,
          description: data.description,
        };
        setEvents(events.map((event) => (event.id === selectedEvent.id ? updatedEvent : event)));
        handleClose();
      },
    });
  }

  function handleDelete() {
    if (!selectedEvent) return;
    openConfirmation({
      title: 'Delete event',
      description: 'Are you sure you want to delete this event? This action cannot be undone.',
      actionLabel: 'Delete',
      actionVariant: 'destructive',
      cancelLabel: 'Cancel',
      onAction: () => {
        setManageEventDialogOpen(false);
        submit(CalendarEventController.destroy(selectedEvent.id), {
          onSuccess: () => {
            setEvents(events.filter((event) => event.id !== selectedEvent.id));
            handleClose();
          },
          onError: () => {
            // Re-open the manage dialog if deletion fails, since we already closed it when the user confirmed
            setManageEventDialogOpen(true);
          },
        });
      },
    });
  }

  const handleClose = useCallback(() => {
    setManageEventDialogOpen(false);
    setSelectedEvent(null);
  }, [setManageEventDialogOpen, setSelectedEvent]);

  return (
    <Dialog open={manageEventDialogOpen} onOpenChange={handleClose}>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Manage event</DialogTitle>
          <DialogDescription hidden />
        </DialogHeader>

        <form onSubmit={handleSubmit}>
          <FieldGroup>
            <InputField required label="Title" name="title" value={data.title} onChange={(value) => setData('title', value)} />
            <InputField label="Location" name="location" value={data.location} onChange={(value) => setData('location', value)} />
            <TextareaField label="Description" name="description" value={data.description} onChange={(e) => setData('description', e.target.value)} />
            <DatetimeField required presetHours label="start_at" value={data.start_at} onChange={(value) => value && setData('start_at', value)} />
            <DatetimeField required presetHours label="end_at" value={data.end_at} onChange={(value) => value && setData('end_at', value)} />
            <ColorPicker value={data.color} onChange={(value) => setData('color', value)} />
            <FieldGroup className="flex-row">
              <DialogClose asChild>
                <Button variant="outline">Cancel</Button>
              </DialogClose>
              <SubmitButton isSubmitting={processing}>Update Event</SubmitButton>
              <Button className="ml-auto" type="button" variant="destructive" onClick={handleDelete}>
                Delete Event
              </Button>
            </FieldGroup>
          </FieldGroup>
        </form>
      </DialogContent>
    </Dialog>
  );
}
