import { FileUploader, type FileUploaderProps } from '@/components/custom-ui/file-uploader/file-uploader';
import { FieldContainer } from './FieldContainer';
import { FieldError } from './FieldError';
import { FieldLabel } from './FieldLabel';

export interface FileFieldProps extends Omit<FileUploaderProps, 'onFileChange'> {
  onChange?: (files: File[]) => void;
  label?: string;
  error?: string;
  className?: string;
  disabled?: boolean;
  required?: boolean;
}
export function FileField({ label, error, disabled, onChange, required, className, ...props }: FileFieldProps) {
  return (
    <FieldContainer className={className}>
      <FieldLabel required={required} disabled={disabled} label={label} />
      <FileUploader disabled={disabled} onFileChange={onChange} {...props} />
      <FieldError error={error} />
    </FieldContainer>
  );
}
