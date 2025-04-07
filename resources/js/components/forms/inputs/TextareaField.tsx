import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';
import { Textarea } from '@/components/ui/textarea';
import { cn } from '@/lib/utils';
import { useId } from 'react';
interface TextareaFieldProps extends Omit<React.ComponentProps<typeof Textarea>, 'onChange' | 'id'> {
    error?: string;
    label?: string;
    disabled?: boolean;
    className?: string;
    onChange?: (value: string) => void;
}
export function TextareaField({ error, label, disabled, className, onChange, ...props }: TextareaFieldProps) {
    const id = useId();
    return (
        <FieldContainer className={className}>
            <FieldLabel disabled={disabled} error={error} id={id} label={label} />
            <Textarea
                disabled={disabled}
                id={id}
                className={cn('bg-input', {
                    'border-destructive ring-offset-destructive focus-visible:ring-destructive': error,
                })}
                onChange={(e) => {
                    onChange && onChange(e.target.value);
                }}
                {...props}
            />
            <FieldError error={error} />
        </FieldContainer>
    );
}
