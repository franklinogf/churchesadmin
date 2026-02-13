import { Field, FieldContent, FieldDescription, FieldError, FieldLabel } from '@/components/ui/field';
import { Switch } from '@/components/ui/switch';
import type { InputBaseProps } from '@/types';
import { useId, type ComponentProps } from 'react';

type SwitchFieldProps = InputBaseProps & ComponentProps<typeof Switch>;

export function SwitchField({ label, disabled, error, description, name, defaultChecked, className, ...props }: SwitchFieldProps) {
  const id = useId();
  return (
    <Field orientation="horizontal" data-disabled={disabled} data-invalid={!!error} className={className}>
      <FieldContent>
        <FieldLabel htmlFor={id}>{label}</FieldLabel>
        <FieldDescription>{description}</FieldDescription>
        <FieldError>{error}</FieldError>
      </FieldContent>
      <Switch
        aria-invalid={!!error}
        name={name}
        aria-describedby={description ? `${id}-description` : undefined}
        disabled={disabled}
        defaultChecked={defaultChecked}
        id={id}
        {...props}
      />
    </Field>
  );
}
