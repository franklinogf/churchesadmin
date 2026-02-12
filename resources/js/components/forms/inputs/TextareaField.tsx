import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { Textarea } from '@/components/ui/textarea';
import type { InputBaseProps } from '@/types';
import { useId, type ComponentProps } from 'react';

type TextareaFieldProps = InputBaseProps & ComponentProps<typeof Textarea>;

export function TextareaField({ error, label, disabled, className, required, ...props }: TextareaFieldProps) {
  const id = useId();
  return (
    <Field className={className} data-disabled={disabled} data-invalid={!!error}>
      <FieldLabel htmlFor={id}>{label}</FieldLabel>
      <Textarea
        id={id}
        className={error ? 'border-destructive ring-offset-destructive focus-visible:ring-destructive' : 'bg-transparent'}
        disabled={disabled}
        required={required}
        {...props}
      />
      <FieldError>{error}</FieldError>
    </Field>
  );
}
