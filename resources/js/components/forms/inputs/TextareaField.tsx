import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';
import { Textarea } from '@/components/ui/textarea';
import { useId } from 'react';
interface TextareaFieldProps {
  error?: string;
  label?: string;
  disabled?: boolean;
  className?: string;
  onChange: (value: string) => void;
  required?: boolean;
  value: string;
}
export function TextareaField({ error, label, disabled, className, onChange, value, required }: TextareaFieldProps) {
  const id = useId();
  return (
    <FieldContainer className={className}>
      <FieldLabel disabled={disabled} required={required} id={id} label={label} />
      <Textarea
        id={id}
        className={error ? 'border-destructive ring-offset-destructive focus-visible:ring-destructive' : 'bg-transparent'}
        value={value}
        onChange={(e) => {
          onChange(e.target.value);
        }}
        disabled={disabled}
        required={required}
      />
      <FieldError error={error} />
    </FieldContainer>
  );
}
