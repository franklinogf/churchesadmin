import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';

import { DateTimePicker } from '@/components/custom-ui/datetime-picker/datetime-picker';
import { useLocaleDate } from '@/hooks/use-locale-date';
import { format } from 'date-fns';

interface DatetimeFieldProps {
  error?: string;
  label?: string;
  disabled?: boolean;
  className?: string;
  placeholder?: string;
  value?: string;
  required?: boolean;
  onChange?: (value: string) => void;
  granularity?: 'day' | 'minute' | 'hour';
}
export function DatetimeField({
  label,
  error,
  className,
  disabled,
  placeholder,
  value,
  onChange,
  required,
  granularity = 'minute',
}: DatetimeFieldProps) {
  const { getCurrentDateLocale } = useLocaleDate();
  return (
    <FieldContainer className={className}>
      <FieldLabel disabled={disabled} label={label} required={required} />
      <DateTimePicker
        placeholder={placeholder}
        showOutsideDays={false}
        locale={getCurrentDateLocale()}
        disabled={disabled}
        value={value ? new Date(value) : undefined}
        granularity={granularity}
        onChange={(date) => {
          onChange?.(date ? format(date, 'yyyy-MM-dd HH:mm:ss') : '');
        }}
      />

      <FieldError error={error} />
    </FieldContainer>
  );
}
