import { PhoneInput } from '@/components/custom-ui/PhoneInput';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';
import { Field, FieldError } from '@/components/ui/field';
import { useId } from 'react';
interface PhoneFieldProps {
  error?: string;
  label?: string;
  disabled?: boolean;
  className?: string;
  placeholder?: string;
  value?: string;
  onChange?: (value: string) => void;
  required?: boolean;
}

export function PhoneField({ error, label, disabled, className, placeholder, value, onChange, required }: PhoneFieldProps) {
  const id = useId();
  return (
    <Field className={className}>
      <FieldLabel disabled={disabled} id={id} label={label} required={required} />
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
      <FieldError>{error}</FieldError>
    </Field>
  );
}
