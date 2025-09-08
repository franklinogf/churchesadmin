import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';

import { DateTimePicker, type DateTimePickerProps } from '@/components/custom-ui/datetime-picker/datetime-picker';
import { useLocaleDate } from '@/hooks/use-locale-date';
import { format, parse } from 'date-fns';

interface DatetimeFieldProps extends Omit<DateTimePickerProps, 'onChange' | 'value'> {
  error?: string;
  label?: string;
  className?: string;
  value?: string;
  required?: boolean;
  onChange?: (value: string) => void;
}
export function DatetimeField({ label, error, className, disabled, value, onChange, required, ...props }: DatetimeFieldProps) {
  const { getCurrentDateLocale } = useLocaleDate();
  const datetimeFormat = props.hideTime ? 'yyyy-MM-dd' : 'yyyy-MM-dd HH:mm:ss';
  return (
    <FieldContainer className={className}>
      <FieldLabel disabled={disabled} label={label} required={required} />
      <DateTimePicker
        classNames={{ trigger: 'bg-input/20' }}
        use12HourFormat
        locale={getCurrentDateLocale()}
        disabled={disabled}
        value={value ? parse(value, datetimeFormat, new Date()) : undefined}
        onChange={(date) => {
          onChange?.(date ? format(date, datetimeFormat) : '');
        }}
        {...props}
      />

      <FieldError error={error} />
    </FieldContainer>
  );
}
