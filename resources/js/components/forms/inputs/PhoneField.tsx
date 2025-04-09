import { PhoneInput } from '@/components/custom-ui/PhoneInput';
import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';
import { useId } from 'react';
interface PhoneFieldProps {
    error?: string;
    label?: string;
    disabled?: boolean;
    className?: string;
    placeholder?: string;
    value?: string;
    onChange?: (value: string) => void;
}
export function PhoneField({ error, label, disabled, className, placeholder, value, onChange }: PhoneFieldProps) {
    const id = useId();
    return (
        <FieldContainer className={className}>
            <FieldLabel disabled={disabled} id={id} label={label} />
            <PhoneInput
                countrySelectProps={{ id: `${id}-country` }}
                numberInputProps={{ id }}
                international
                placeholder={placeholder}
                defaultCountry="PR"
                disabled={disabled}
                value={value}
                onChange={(value) => {
                    onChange?.(value);
                }}
            />
            <FieldError error={error} />
        </FieldContainer>
    );
}
