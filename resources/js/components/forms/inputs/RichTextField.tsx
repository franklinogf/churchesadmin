import { RichTextEditor } from '@/components/custom-ui/rich-text-editor/rich-text-editor';
import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { useId } from 'react';
import { FieldLabel } from './FieldLabel';

interface RichTextFieldProps {
  value: string;
  onChange: (value: string) => void;
  className?: string;
  error?: string;
  label?: string;
  required?: boolean;
}
export function RichTextField({ value, onChange, className, error, label, required }: RichTextFieldProps) {
  const id = useId();
  return (
    <FieldContainer className={className}>
      <FieldLabel required={required} label={label} />
      <RichTextEditor id={id} value={value} onChange={onChange} />
      <FieldError error={error} />
    </FieldContainer>
  );
}
