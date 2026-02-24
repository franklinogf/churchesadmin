import { FieldLabel } from '@/components/forms/inputs/FieldLabel';

import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Field, FieldError, FieldGroup } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useLocaleDate } from '@/hooks/use-locale-date';
import { useTranslations } from '@/hooks/use-translations';
import { parseLocalDate } from '@/lib/datetime';
import { format } from 'date-fns';
import { ChevronDownIcon } from 'lucide-react';
import { useId, useState } from 'react';

interface DatetimeFieldProps {
  error?: string;
  label?: string;
  timeLabel?: string;
  className?: string;
  value?: Date | null;
  defaultValue?: Date | null;
  required?: boolean;
  onChange?: (value: Date | null) => void;
  disabled?: boolean;
  presetHours?: boolean;
  maxDate?: Date | 'today';
  minDate?: Date | 'today';
  use24HourFormat?: boolean;
  name?: string;
}

const generateTimeSlots = (use24HourFormat: boolean) => {
  const values = Array.from({ length: 96 }).map((_, i) => {
    const hour = Math.floor(i / 4)
      .toString()
      .padStart(2, '0');
    const minute = ((i % 4) * 15).toString().padStart(2, '0');
    return `${hour}:${minute}`;
  });

  return values.map((value) => {
    const [hour, minute] = value.split(':').map(Number);
    const date = new Date();
    date.setHours(hour!, minute, 0, 0);
    return {
      value,
      label: format(date, use24HourFormat ? 'HH:mm' : 'hh:mm aa'),
    };
  });
};

export function DatetimeField({
  label,
  timeLabel,
  error,
  className,
  disabled,
  value,
  onChange,
  required,
  presetHours,
  maxDate,
  minDate,
  use24HourFormat = false,
  name,
  defaultValue,
}: DatetimeFieldProps) {
  const { getCurrentDateLocale } = useLocaleDate();
  const { t } = useTranslations();
  const dateId = useId();
  const timeId = useId();
  const [open, setOpen] = useState(false);
  const [date, setDate] = useState(value ?? defaultValue ?? undefined);
  const [time, setTime] = useState<string | undefined>(date ? format(date, 'hh:mm') : undefined);

  const TIME_SLOTS = generateTimeSlots(use24HourFormat);

  const handleDateChange = (selectedDate: Date | null) => {
    setDate(selectedDate ?? undefined);
    if (selectedDate && time) {
      const [hours, minutes] = time.split(':').map(Number);
      selectedDate.setHours(hours || 0, minutes || 0, 0, 0);
    }
    onChange?.(selectedDate);
  };

  const handleTimeChange = (selectedTime: string) => {
    setTime(selectedTime);
    if (date && selectedTime) {
      const newDate = new Date(date.getTime());
      const [hours, minutes] = selectedTime.split(':').map(Number);
      newDate.setHours(hours || 0, minutes || 0, 0, 0);
      setDate(newDate);
      onChange?.(newDate);
    }
  };

  let hidden: { after: Date; before: Date } | undefined = undefined;
  if (maxDate || minDate) {
    hidden = {
      after: maxDate === 'today' ? new Date() : (maxDate ?? parseLocalDate('2100-01-01')),
      before: minDate === 'today' ? new Date() : (minDate ?? parseLocalDate('1900-01-01')),
    };
  }

  return (
    <FieldGroup className={className}>
      {name && <input type="hidden" name={name} value={date ? date.toISOString() : ''} />}
      <FieldGroup className="flex-row">
        <Field>
          <FieldLabel id={dateId} disabled={disabled} label={label} required={required} />
          <Popover open={open} onOpenChange={setOpen}>
            <PopoverTrigger asChild>
              <Button variant="outline" id={dateId} className="w-full justify-between font-normal">
                {value ? format(value, 'PPP', { locale: getCurrentDateLocale() }) : t('Select a date')}
                <ChevronDownIcon />
              </Button>
            </PopoverTrigger>
            <PopoverContent className="w-content overflow-hidden p-0" align="start">
              <Calendar
                hidden={hidden}
                disabled={disabled}
                locale={getCurrentDateLocale()}
                mode="single"
                className="w-full"
                selected={date}
                captionLayout="dropdown"
                defaultMonth={date}
                onSelect={(newDate) => {
                  handleDateChange(newDate || null);
                  setOpen(false);
                }}
              />
            </PopoverContent>
          </Popover>
        </Field>
        <Field className="self-end">
          <FieldLabel id={timeId} disabled={disabled} label={timeLabel} required={required} />
          {presetHours ? (
            <Select
              disabled={disabled || !date}
              value={time}
              onValueChange={(e) => {
                handleTimeChange(e);
              }}
            >
              <SelectTrigger className="w-30 font-normal focus:ring-0 focus:ring-offset-0">
                <SelectValue />
              </SelectTrigger>
              <SelectContent>
                <ScrollArea className="h-60">
                  {TIME_SLOTS.map((slot) => (
                    <SelectItem key={slot.value} value={slot.value}>
                      {slot.label}
                    </SelectItem>
                  ))}
                </ScrollArea>
              </SelectContent>
            </Select>
          ) : (
            <Input
              id={timeId}
              type="time"
              disabled={disabled || !date}
              step="15"
              value={time}
              onChange={(e) => {
                const time = e.target.value;
                handleTimeChange(time);
              }}
              className="bg-background appearance-none [&::-webkit-calendar-picker-indicator]:hidden [&::-webkit-calendar-picker-indicator]:appearance-none"
            />
          )}
        </Field>
      </FieldGroup>
      <FieldError>{error}</FieldError>
    </FieldGroup>
  );
}
