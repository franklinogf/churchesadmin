import { CountryDropdown } from '@/components/custom-ui/CountryDropdown';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';
import { Field, FieldError } from '@/components/ui/field';

interface CountryFieldProps {
  disabled?: boolean;
  className?: string;
  placeholder?: string;
  value?: string;
  onChange?: (value: string) => void;
  error?: string;
  label?: string;
  clearable?: boolean;
  required?: boolean;
}

export function CountryField({ error, label, disabled, className, value, onChange, placeholder, clearable, required }: CountryFieldProps) {
  return (
    <Field className={className}>
      <FieldLabel required={required} disabled={disabled} label={label} />
      <CountryDropdown
        placeholder={placeholder}
        defaultValue={value}
        onChange={(country) => {
          onChange?.(country);
        }}
        clearable={clearable}
      />
      <FieldError>{error}</FieldError>
    </Field>
  );
}
