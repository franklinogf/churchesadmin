import { CountryDropdown } from '@/components/custom-ui/CountryDropdown';
import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';

interface CountryFieldProps {
    disabled?: boolean;
    className?: string;
    placeholder?: string;
    value?: string;
    onChange?: (value: string) => void;
    error?: string;
    label?: string;
    fieldClassName?: string;
}

export function CountryField({ error, label, disabled, className, value, onChange, fieldClassName, placeholder }: CountryFieldProps) {
    return (
        <FieldContainer className={className}>
            <FieldLabel disabled={disabled} label={label} />
            <CountryDropdown
                placeholder={placeholder}
                defaultValue={value}
                onChange={(country) => {
                    onChange?.(country);
                }}
            />
            <FieldError error={error} />
        </FieldContainer>
    );
}
