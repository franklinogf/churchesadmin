import { Dialog, DialogClose, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { useCallback, useEffect, useState, type SubmitEvent } from 'react';

import CalendarEventController from '@/actions/App/Http/Controllers/CalendarEventController';
import SendCalendarEventToMembersController from '@/actions/App/Http/Controllers/SendCalendarEventToMembersController';
import { ColorPicker } from '@/components/forms/inputs/color-picker';
import { DatetimeField } from '@/components/forms/inputs/DatetimeField';
import { InputField } from '@/components/forms/inputs/InputField';
import { TextareaField } from '@/components/forms/inputs/TextareaField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { Button } from '@/components/ui/button';
import { FieldGroup } from '@/components/ui/field';
import { CalendarEventColorEnum } from '@/enums/CalendarEventColorEnum';
import { useTranslations } from '@/hooks/use-translations';
import useConfirmationStore from '@/stores/confirmationStore';
import type { CalendarEvent } from '@/types/models/calendar-event';
import { useForm } from '@inertiajs/react';
import { MailsIcon } from 'lucide-react';
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
  const { t } = useTranslations();
  const { manageEventDialogOpen, setManageEventDialogOpen, selectedEvent, setSelectedEvent, events, setEvents } = useCalendarContext();
  const { openConfirmation } = useConfirmationStore();

  const { data, setData, submit, processing, errors } = useForm<CalendarForm>({
    title: '',
    start_at: selectedEvent ? new Date(selectedEvent.startAt) : new Date(),
    end_at: new Date(),
    color: CalendarEventColorEnum.BLUE,
    location: '',
    description: '',
  });
  const [isLoading, setIsLoading] = useState(processing);

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
      onFinish: () => {
        setIsLoading(false);
      },
    });
  }

  function handleDelete() {
    if (!selectedEvent) return;
    openConfirmation({
      title: t('Delete event'),
      description: t('Are you sure you want to delete this event? This action cannot be undone.'),
      actionLabel: t('Delete'),
      actionVariant: 'destructive',
      cancelLabel: t('Cancel'),
      onAction: () => {
        setManageEventDialogOpen(false);

        submit(CalendarEventController.destroy(selectedEvent.id), {
          onStart: () => {
            setIsLoading(true);
          },
          onSuccess: () => {
            setEvents(events.filter((event) => event.id !== selectedEvent.id));
            handleClose();
          },
          onError: () => {
            // Re-open the manage dialog if deletion fails, since we already closed it when the user confirmed
            setManageEventDialogOpen(true);
          },
          onFinish: () => {
            setIsLoading(false);
          },
        });
      },
    });
  }

  const handleClose = useCallback(() => {
    setManageEventDialogOpen(false);
    setSelectedEvent(null);
  }, [setManageEventDialogOpen, setSelectedEvent]);

  const handleSendEmail = useCallback(() => {
    if (!selectedEvent) return;
    submit(SendCalendarEventToMembersController(selectedEvent.id), {
      onStart: () => {
        setIsLoading(true);
      },
      onSuccess: () => {
        handleClose();
      },
      onFinish: () => {
        setIsLoading(false);
      },
    });
  }, [submit, selectedEvent, handleClose]);

  return (
    <Dialog open={manageEventDialogOpen} onOpenChange={handleClose}>
      <DialogContent showCloseButton={false}>
        <DialogHeader className="flex-row items-center">
          <DialogTitle>{t('Manage event')}</DialogTitle>
          <Button disabled={isLoading} className="ml-auto" variant="outline" size="sm" onClick={handleSendEmail}>
            <MailsIcon />
            {t('Send email to members')}
          </Button>
          <DialogDescription hidden />
        </DialogHeader>

        <form onSubmit={handleSubmit}>
          <FieldGroup>
            <FieldGroup>
              <InputField
                required
                label={t('Title')}
                name="title"
                value={data.title}
                onChange={(value) => setData('title', value)}
                error={errors.title}
              />
              <InputField
                label={t('Location')}
                name="location"
                value={data.location}
                onChange={(value) => setData('location', value)}
                error={errors.location}
              />
              <TextareaField
                label={t('Description')}
                name="description"
                value={data.description}
                onChange={(e) => setData('description', e.target.value)}
                error={errors.description}
              />
              <DatetimeField
                required
                presetHours
                label={t('Start at')}
                value={data.start_at}
                onChange={(value) => value && setData('start_at', value)}
                error={errors.start_at}
              />
              <DatetimeField
                required
                presetHours
                label={t('End at')}
                value={data.end_at}
                onChange={(value) => value && setData('end_at', value)}
                error={errors.end_at}
              />
              <ColorPicker value={data.color} onChange={(value) => setData('color', value)} />
            </FieldGroup>

            <FieldGroup className="flex-row justify-end">
              <DialogClose asChild>
                <Button disabled={isLoading} variant="outline">
                  {t('Cancel')}
                </Button>
              </DialogClose>
              <SubmitButton isSubmitting={processing}>{t('Update Event')}</SubmitButton>
              <Button disabled={isLoading} className="ml-auto" type="button" variant="destructive" onClick={handleDelete}>
                {t('Delete Event')}
              </Button>
            </FieldGroup>
          </FieldGroup>
        </form>
      </DialogContent>
    </Dialog>
  );
}
