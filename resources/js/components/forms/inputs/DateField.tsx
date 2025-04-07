import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';
import { format } from 'date-fns';
import { useId } from 'react';

import { DatePicker } from '@/components/custom-ui/DatePicker';
import { formatStringToDate } from '@/lib/datetime';
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
    onChange?: (value: string) => void;
}
export function DateField({
    label,
    error,
    className,
    disabled,
    clearable = true,
    value,
    startYear = 2020,
    endYear = 2030,
    onChange,
}: DateFieldProps) {
    // const { t, currentLocale } = useLaravelReactI18n();

    const id = useId();

    return (
        <FieldContainer className={className}>
            <FieldLabel disabled={disabled} error={error} id={id} label={label} />

            <DatePicker
                disabled={disabled}
                startYear={startYear}
                endYear={endYear}
                selected={value ? new Date(formatStringToDate(value) || '') : new Date()}
                onSelect={(date) => {
                    const formattedDate = format(date, 'yyyy-MM-dd');
                    onChange?.(formattedDate);
                }}
            />

            <FieldError error={error} />
        </FieldContainer>
    );
}
