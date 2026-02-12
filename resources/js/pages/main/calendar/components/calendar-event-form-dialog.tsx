import CalendarEventController from '@/actions/App/Http/Controllers/CalendarEventController';
import { DatetimeField } from '@/components/forms/inputs/DatetimeField';
import { InputField } from '@/components/forms/inputs/InputField';
import { TextareaField } from '@/components/forms/inputs/TextareaField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { ResponsiveModal } from '@/components/responsive-modal';
import { Button } from '@/components/ui/button';
import { DrawerClose } from '@/components/ui/drawer';
import { TenantPermission } from '@/enums/TenantPermission';
import { useTranslations } from '@/hooks/use-translations';
import { useUser } from '@/hooks/use-user';
import useConfirmationStore from '@/stores/confirmationStore';
import type { CalendarEvent } from '@/types/models/calendar-event';
import { useForm } from '@inertiajs/react';
import { addMinutes, endOfDay, startOfDay } from 'date-fns';
import { Trash2Icon } from 'lucide-react';
import { useEffect } from 'react';

type CalendarEventFormData = {
  title: string;
  description: string;
  location: string;
  start_at: Date | null;
  end_at: Date | null;
};
interface CalendarEventFormDialogProps {
  mode: 'create' | 'edit';
  event?: CalendarEvent;
  open: boolean;
  setOpen: (open: boolean) => void;
  prefilledDate?: Date;
  onDelete?: (event: CalendarEvent) => void;
}

export function CalendarEventFormDialog({ mode, event, open, setOpen, prefilledDate, onDelete }: CalendarEventFormDialogProps) {
  const { t } = useTranslations();
  const { can: userCan } = useUser();
  const openConfirmation = useConfirmationStore((state) => state.openConfirmation);
  const { data, setData, submit, errors, reset, processing, transform } = useForm<CalendarEventFormData>({
    title: '',
    description: '',
    location: '',
    start_at: startOfDay(new Date()),
    end_at: endOfDay(new Date()),
  });

  // Update form data when event changes or modal opens
  useEffect(() => {
    if (open) {
      if (mode === 'edit' && event) {
        setData({
          title: event.title,
          description: event.description ?? '',
          location: event.location ?? '',
          start_at: new Date(event.startAt),
          end_at: new Date(event.endAt),
        });
      } else {
        // Reset to defaults for create mode, use prefilled date if available
        const startDate = prefilledDate || startOfDay(new Date());
        const endDate = new Date(addMinutes(startDate, 15)); // Default to 15 minutes duration

        setData({
          title: '',
          description: '',
          location: '',
          start_at: startDate,
          end_at: endDate,
        });
      }
    }
  }, [open, mode, event, prefilledDate, setData]);

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    transform((data) => ({
      ...data,
      start_at: data.start_at?.toISOString(),
      end_at: data.end_at?.toISOString(),
    }));

    if (mode === 'edit' && event) {
      submit(CalendarEventController.update(event.id), {
        only: ['events'],
        onSuccess: () => {
          setOpen(false);
        },
      });
    } else {
      submit(CalendarEventController.store(), {
        preserveState: 'errors',
        onSuccess: () => {
          setOpen(false);
          reset();
        },
      });
    }
  }

  function handleEventDelete(event: CalendarEvent) {
    if (!onDelete) return;
    openConfirmation({
      title: t('Delete :model', { model: t('Event') }),
      description: t('Are you sure you want to delete this :model?', { model: t('Event') }),
      onAction: () => {
        onDelete(event);
        setOpen(false);
      },
      actionLabel: t('Delete'),
      actionVariant: 'destructive',
      cancelLabel: t('Cancel'),
    });
  }

  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={mode === 'edit' ? t('Edit :model', { model: t('Event') }) : t('Add :model', { model: t('Event') })}
      description={mode === 'edit' ? t('Edit the details of this :model', { model: t('Event') }) : t('Create a new :model', { model: t('Event') })}
    >
      <form className="space-y-4" onSubmit={handleSubmit}>
        <InputField required label={t('Event title')} value={data.title} onChange={(value) => setData('title', value)} error={errors.title} />

        <TextareaField
          label={t('Event description')}
          value={data.description}
          onChange={(e) => setData('description', e.target.value)}
          error={errors.description}
        />

        <InputField label={t('Event location')} value={data.location} onChange={(value) => setData('location', value)} error={errors.location} />

        <DatetimeField
          required
          presetHours
          label={t('Start date & time')}
          value={data.start_at}
          onChange={(value) => {
            setData('start_at', value);
          }}
          error={errors.start_at}
        />

        <DatetimeField
          presetHours
          required
          label={t('End date & time')}
          value={data.end_at}
          onChange={(value) => {
            setData('end_at', value);
          }}
          error={errors.end_at}
        />

        {/* Footer */}
        <div className="grid grid-cols-1 gap-2 md:flex md:justify-between md:gap-4">
          {mode === 'edit' && event && userCan(TenantPermission.CALENDAR_EVENTS_DELETE) && onDelete ? (
            <Button type="button" variant="destructive" onClick={() => handleEventDelete(event)} className="order-3 max-md:w-full md:order-1">
              <Trash2Icon className="mr-1 size-4" />
              {t('Delete')}
            </Button>
          ) : (
            <div></div>
          )}
          <div className="flex gap-2 md:gap-4">
            <DrawerClose asChild>
              <Button variant="outline" className="order-2 max-md:w-full md:order-1">
                {t('Cancel')}
              </Button>
            </DrawerClose>
            <SubmitButton className="order-1 max-md:w-full md:order-2" isSubmitting={processing}>
              {t('Save')}
            </SubmitButton>
          </div>
        </div>
      </form>
    </ResponsiveModal>
  );
}
