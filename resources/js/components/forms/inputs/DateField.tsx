import { FieldLabel } from '@/components/forms/inputs/FieldLabel';

import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Field, FieldError } from '@/components/ui/field';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { useLocaleDate } from '@/hooks/use-locale-date';
import { useTranslations } from '@/hooks/use-translations';
import { parseLocalDate } from '@/lib/datetime';
import { format } from 'date-fns';
import { ChevronDownIcon } from 'lucide-react';
import { useId, useState } from 'react';

interface DatetimeFieldProps {
  error?: string;
  label?: string;
  className?: string;
  value?: string | null;
  required?: boolean;
  onChange?: (value: string | null) => void;
  disabled?: boolean;
  maxDate?: Date | 'today';
  minDate?: Date | 'today';
}

export function DateField({ label, error, className, disabled, value, onChange, required, maxDate, minDate }: DatetimeFieldProps) {
  const { getCurrentDateLocale } = useLocaleDate();
  const { t } = useTranslations();
  const id = useId();
  const [open, setOpen] = useState(false);

  const handleDateChange = (selectedDate: Date | null) => {
    onChange?.(selectedDate ? format(selectedDate, 'yyyy-MM-dd') : null);
  };

  let hidden: { after: Date; before: Date } | undefined = undefined;
  if (maxDate || minDate) {
    hidden = {
      after: maxDate === 'today' ? new Date() : (maxDate ?? parseLocalDate('2100-01-01')),
      before: minDate === 'today' ? new Date() : (minDate ?? parseLocalDate('1900-01-01')),
    };
  }
  return (
    <Field className={className}>
      <FieldLabel id={id} disabled={disabled} label={label} required={required} />
      <Popover open={open} onOpenChange={setOpen}>
        <PopoverTrigger asChild>
          <Button disabled={disabled} variant="outline" id={id} className="w-full justify-between font-normal">
            {value ? format(parseLocalDate(value), 'PPP', { locale: getCurrentDateLocale() }) : t('Select a date')}
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
            selected={value ? parseLocalDate(value) : undefined}
            captionLayout="dropdown"
            defaultMonth={value ? parseLocalDate(value) : undefined}
            onSelect={(newDate) => {
              handleDateChange(newDate || null);
              setOpen(false);
            }}
          />
        </PopoverContent>
      </Popover>
      <FieldError>{error}</FieldError>
    </Field>
  );
}
