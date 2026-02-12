import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group';
import { cn } from '@/lib/utils';
import type { InputBaseProps } from '@/types';
import { useId, type ComponentProps } from 'react';
import CurrencyInput from 'react-currency-input-field';

type CurrencyFieldProps = InputBaseProps & Omit<ComponentProps<typeof CurrencyInput>, 'onFocus' | 'customInput'>;

export function CurrencyField({
  required,
  error,
  label,
  disabled,
  className,
  placeholder = '0.00',
  decimalScale = 2,
  allowNegativeValue = false,
  ...props
}: CurrencyFieldProps) {
  const id = useId();
  return (
    <Field data-disabled={disabled} data-invalid={!!error} className={className}>
      <FieldLabel htmlFor={id}>{label}</FieldLabel>
      <InputGroup>
        <CurrencyInput
          id={id}
          disabled={disabled}
          required={required}
          className={cn({
            'border-red-600 ring-offset-red-600 focus-visible:ring-red-600 dark:border-red-400 dark:ring-offset-red-400 dark:focus-visible:ring-red-400':
              error,
          })}
          onFocus={(e) => {
            e.target.select();
          }}
          customInput={InputGroupInput}
          allowNegativeValue={allowNegativeValue}
          decimalScale={decimalScale}
          placeholder={placeholder}
          {...props}
        />
        <InputGroupAddon>$</InputGroupAddon>
      </InputGroup>

      <FieldError>{error}</FieldError>
    </Field>
  );
}
