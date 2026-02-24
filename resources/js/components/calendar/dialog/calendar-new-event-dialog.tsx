import CalendarEventController from '@/actions/App/Http/Controllers/CalendarEventController';
import { ColorPicker } from '@/components/forms/inputs/color-picker';
import { DatetimeField } from '@/components/forms/inputs/DatetimeField';
import { InputField } from '@/components/forms/inputs/InputField';
import { TextareaField } from '@/components/forms/inputs/TextareaField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { FieldGroup } from '@/components/ui/field';
import { CalendarEventColorEnum } from '@/enums/CalendarEventColorEnum';
import { useTranslations } from '@/hooks/use-translations';
import type { CalendarEvent } from '@/types/models/calendar-event';
import { useForm } from '@inertiajs/react';
import { addMinutes, isToday, startOfDay } from 'date-fns';
import { useCallback, useEffect, type SubmitEvent } from 'react';
import { useCalendarContext } from '../calendar-context';
interface CalendarForm {
  title: string;
  location: string;
  description: string;
  start_at: Date;
  end_at: Date;
  color: CalendarEventColorEnum;
}
export default function CalendarNewEventDialog() {
  const { t } = useTranslations();
  const { newEventDialogOpen, setNewEventDialogOpen, date, setEvents } = useCalendarContext();

  const { data, setData, reset, submit, setDefaults, errors, processing } = useForm<CalendarForm>({
    title: '',
    location: '',
    description: '',
    start_at: new Date(),
    end_at: new Date(),
    color: CalendarEventColorEnum.BLUE,
  });
  useEffect(() => {
    const dateIsToday = isToday(date);
    const selectedDate = dateIsToday ? new Date() : date;
    const startDate = dateIsToday ? addMinutes(selectedDate, 30 - (selectedDate.getMinutes() % 30)) : startOfDay(date);
    const endDate = addMinutes(startDate, 30);
    setDefaults({
      start_at: startDate,
      end_at: endDate,
    });
    setData('start_at', startDate);
    setData('end_at', endDate);
  }, [date, setData, setDefaults]);

  const handleClose = useCallback(() => {
    setNewEventDialogOpen(false);
    reset();
  }, [reset, setNewEventDialogOpen]);

  const handleSubmit = useCallback(
    (e: SubmitEvent<HTMLFormElement>) => {
      e.preventDefault();
      submit(CalendarEventController.store(), {
        onSuccess: (payload) => {
          setEvents(payload.props.events as CalendarEvent[]);
          handleClose();
        },
      });
    },
    [submit, setEvents, handleClose],
  );

  return (
    <Dialog open={newEventDialogOpen} onOpenChange={handleClose}>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>{t('Create event')}</DialogTitle>
          <DialogDescription hidden />
        </DialogHeader>

        <form onSubmit={handleSubmit}>
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
            <FieldGroup className="flex-row justify-end">
              <DialogClose asChild>
                <Button variant="outline" type="button">
                  {t('Cancel')}
                </Button>
              </DialogClose>
              <SubmitButton isSubmitting={processing}>{t('Create Event')}</SubmitButton>
            </FieldGroup>
          </FieldGroup>
        </form>
      </DialogContent>
    </Dialog>
  );
}
