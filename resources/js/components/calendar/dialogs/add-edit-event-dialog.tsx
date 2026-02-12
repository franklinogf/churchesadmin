import CalendarEventController from '@/actions/App/Http/Controllers/CalendarEventController';
import { COLORS } from '@/components/calendar/constants';
import { useDisclosure } from '@/components/calendar/hooks';
import type { IEvent } from '@/components/calendar/interfaces';
import type { TEventColor } from '@/components/calendar/types';
import { DatetimeField } from '@/components/forms/inputs/DatetimeField';
import { InputField } from '@/components/forms/inputs/InputField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { TextareaField } from '@/components/forms/inputs/TextareaField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { Button } from '@/components/ui/button';
import { FieldGroup } from '@/components/ui/field';
import {
  Modal,
  ModalClose,
  ModalContent,
  ModalDescription,
  ModalFooter,
  ModalHeader,
  ModalTitle,
  ModalTrigger,
} from '@/components/ui/responsive-modal';
import { SelectItem } from '@/components/ui/select';
import { CalendarEventColorEnum } from '@/enums/CalendarEventColorEnum';
import { useForm } from '@inertiajs/react';
import { addMinutes, set } from 'date-fns';
import { type ReactNode, type SubmitEvent, useMemo } from 'react';
import { toast } from 'sonner';
import { useCalendar } from '../contexts/calendar-context';
interface IProps {
  children: ReactNode;
  startDate?: Date;
  startTime?: { hour: number; minute: number };
  event?: IEvent;
}

type EventFormData = {
  title: string;
  description: string;
  location: string | null;
  start_at: Date | null;
  end_at: Date | null;
  color: TEventColor;
};

export function AddEditEventDialog({ children, startDate, startTime, event }: IProps) {
  const { isOpen, onClose, onToggle } = useDisclosure();
  const { use24HourFormat, setEvents } = useCalendar();
  const isEditing = !!event;

  const initialDates = useMemo(() => {
    if (!isEditing && !event) {
      if (!startDate) {
        const now = new Date();
        return { startDate: now, endDate: addMinutes(now, 30) };
      }
      const start = startTime
        ? set(new Date(startDate), {
            hours: startTime.hour,
            minutes: startTime.minute,
            seconds: 0,
          })
        : new Date(startDate);
      const end = addMinutes(start, 15);
      return { startDate: start, endDate: end };
    }

    return {
      startDate: new Date(event.startAt),
      endDate: new Date(event.endAt),
    };
  }, [startDate, startTime, event, isEditing]);

  const { data, setData, errors, clearErrors, submit, processing } = useForm<EventFormData>({
    title: event?.title ?? '',
    description: event?.description ?? '',
    location: event?.location ?? null,
    start_at: initialDates.startDate,
    end_at: initialDates.endDate,
    color: event?.color ?? CalendarEventColorEnum.BLUE,
  });

  const handleSubmit = (e: SubmitEvent<HTMLFormElement>) => {
    e.preventDefault();
    clearErrors();
    submit(isEditing ? CalendarEventController.update(event.id) : CalendarEventController.store(), {
      only: ['events'],
      onError: (error) => {
        console.error(`Error ${isEditing ? 'editing' : 'adding'} event:`, error);
        toast.error(`Failed to ${isEditing ? 'edit' : 'add'} event`);
      },
      onSuccess: (payload) => {
        setEvents(payload.props.events as IEvent[]);
        onClose();
      },
    });
  };

  return (
    <Modal open={isOpen} onOpenChange={onToggle} modal={false}>
      <ModalTrigger asChild>{children}</ModalTrigger>
      <ModalContent>
        <ModalHeader>
          <ModalTitle>{isEditing ? 'Edit Event' : 'Add New Event'}</ModalTitle>
          <ModalDescription>{isEditing ? 'Modify your existing event.' : 'Create a new event for your calendar.'}</ModalDescription>
        </ModalHeader>

        <form id="event-form" onSubmit={handleSubmit}>
          <FieldGroup className="gap-2">
            <InputField
              value={data.title}
              onChange={(value) => setData('title', value)}
              placeholder="Enter a title"
              label="Title"
              required
              error={errors.title}
            />

            <InputField
              value={data.location || ''}
              onChange={(value) => setData('location', value)}
              placeholder="Enter a location"
              label="Location"
              error={errors.location}
            />

            <SelectField
              label="Color"
              value={data.color}
              onValueChange={(value) => setData('color', value as TEventColor)}
              error={errors.color}
              required
            >
              {COLORS.map((color) => (
                <SelectItem value={color} key={color}>
                  <div className="flex items-center gap-2">
                    <div className={`size-3.5 rounded-full bg-${color}-600 dark:bg-${color}-700`} />
                    {color}
                  </div>
                </SelectItem>
              ))}
            </SelectField>

            <DatetimeField
              required
              presetHours
              use24HourFormat={use24HourFormat}
              label="Start date & time"
              value={data.start_at}
              onChange={(value) => setData('start_at', value)}
              error={errors.start_at}
            />

            <DatetimeField
              required
              presetHours
              use24HourFormat={use24HourFormat}
              label="End date & time"
              value={data.end_at}
              onChange={(value) => setData('end_at', value)}
              error={errors.end_at}
            />

            <TextareaField
              label="Description"
              value={data.description}
              onChange={(e) => setData('description', e.target.value)}
              placeholder="Enter a description"
              error={errors.description}
            />
          </FieldGroup>
        </form>
        <ModalFooter className="mt-2 flex justify-end gap-2">
          <ModalClose asChild>
            <Button type="button" variant="outline">
              Cancel
            </Button>
          </ModalClose>
          <SubmitButton form="event-form" isSubmitting={processing}>
            {isEditing ? 'Save Changes' : 'Create Event'}
          </SubmitButton>
        </ModalFooter>
      </ModalContent>
    </Modal>
  );
}
