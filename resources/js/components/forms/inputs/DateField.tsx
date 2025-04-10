import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';

import { DatePicker } from '@/components/custom-ui/DatePicker';
import { formatDateToString, formatStringToDate, getCurrentDateLocale } from '@/lib/datetime';

interface DateFieldProps {
    error?: string;
    label?: string;
    disabled?: boolean;
    className?: string;
    placeholder?: string;
    clearable?: boolean;
    value?: string;
    startYear?: number;
    endYear?: number;
    required?: boolean;
    onChange?: (value: string) => void;
}
export function DateField({
    label,
    error,
    className,
    disabled,
    value,
    startYear = new Date().getFullYear() - 90,
    endYear = new Date().getFullYear(),
    onChange,
    required,
}: DateFieldProps) {
    return (
        <FieldContainer className={className}>
            <FieldLabel disabled={disabled} label={label} required={required} />
            <DatePicker
                locale={getCurrentDateLocale()}
                disabled={disabled}
                startYear={startYear}
                endYear={endYear}
                selected={value ? new Date(formatStringToDate(value)) : null}
                onSelect={(date) => {
                    onChange?.(formatDateToString(date));
                }}
            />

            <FieldError error={error} />
        </FieldContainer>
    );
}
