import { FileUploader, type FileUploaderProps } from '@/components/custom-ui/file-uploader/file-uploader';
import { FieldContainer } from './FieldContainer';
import { FieldError } from './FieldError';
import { FieldLabel } from './FieldLabel';

export interface FileFieldProps extends Omit<FileUploaderProps, 'onFileChange' | 'initialFiles'> {
  onChange?: (files: File[]) => void;
  label?: string;
  error?: string;
  className?: string;
  disabled?: boolean;
  required?: boolean;
  initialFileUrls?: (string[] | null[]) | string | null;
}
export function FileField({ label, error, disabled, onChange, required, className, initialFileUrls: initialFiles, ...props }: FileFieldProps) {
  initialFiles = Array.isArray(initialFiles)
    ? initialFiles.filter((file) => file !== null).map(decodeURI)
    : initialFiles
      ? [decodeURI(initialFiles)]
      : undefined;

  return (
    <FieldContainer className={className}>
      <FieldLabel className="mb-4" required={required} disabled={disabled} label={label} />
      <FileUploader disabled={disabled} onFileChange={onChange} initialFiles={initialFiles} {...props} />
      <FieldError error={error} />
    </FieldContainer>
  );
}
