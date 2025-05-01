import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';
import { Input } from '@/components/ui/input';
import { cn } from '@/lib/utils';
import { useId } from 'react';
import CurrencyInput from 'react-currency-input-field';

interface CurrencyFieldProps {
  onChange?: (value: string) => void;
  error?: string;
  label?: string;
  fieldClassName?: string;
  required?: boolean;
  disabled?: boolean;
  className?: string;
  value?: string;
  placeholder?: string;
  allowNegativeValue?: boolean;
}

export function CurrencyField({
  required,
  error,
  label,
  disabled,
  className,
  value,
  onChange,
  fieldClassName,
  placeholder = '0.00',
  allowNegativeValue = false,

  ...props
}: CurrencyFieldProps) {
  const id = useId();
  return (
    <FieldContainer className={className}>
      <FieldLabel disabled={disabled} id={id} label={label} required={required} />
      <CurrencyInput
        id={id}
        disabled={disabled}
        required={required}
        className={cn(
          {
            'border-red-600 ring-offset-red-600 focus-visible:ring-red-600 dark:border-red-400 dark:ring-offset-red-400 dark:focus-visible:ring-red-400':
              error,
          },
          fieldClassName,
        )}
        onFocus={(e) => {
          e.target.select();
        }}
        customInput={Input}
        allowNegativeValue={allowNegativeValue}
        value={value}
        prefix="$"
        decimalScale={2}
        onValueChange={(value) => {
          onChange?.(value ?? '');
        }}
        placeholder={placeholder}
        {...props}
      />

      <FieldError error={error} />
    </FieldContainer>
  );
}
