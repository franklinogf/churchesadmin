import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';
import { Input } from '@/components/ui/input';
import { cn } from '@/lib/utils';
import { DollarSignIcon } from 'lucide-react';
import { useId } from 'react';
import CurrencyInput, { type CurrencyInputProps } from 'react-currency-input-field';

interface CurrencyFieldProps extends Omit<CurrencyInputProps, 'onChange' | 'id'> {
  onChange?: (value: string) => void;
  error?: string;
  label?: string;
  fieldClassName?: string;
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
  ...props
}: CurrencyFieldProps) {
  const id = useId();
  return (
    <FieldContainer className={className}>
      <FieldLabel disabled={disabled} id={id} label={label} required={required} />
      <div className="relative w-full">
        <div className="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
          <DollarSignIcon className="text-foreground/80 size-3.5" />
        </div>
        <CurrencyInput
          id={id}
          disabled={disabled}
          required={required}
          className={cn(
            'ps-6',
            {
              'border-red-600 ring-offset-red-600 focus-visible:ring-red-600 dark:border-red-400 dark:ring-offset-red-400 dark:focus-visible:ring-red-400':
                error,
            },
            fieldClassName,
          )}
          customInput={Input}
          allowNegativeValue={false}
          value={value}
          fixedDecimalLength={2}
          decimalScale={2}
          onValueChange={(value) => {
            onChange?.(value ?? '');
          }}
          placeholder={placeholder}
          {...props}
        />
      </div>

      <FieldError error={error} />
    </FieldContainer>
  );
}
