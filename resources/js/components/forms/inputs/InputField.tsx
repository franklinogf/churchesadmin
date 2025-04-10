import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';
import { Input } from '@/components/ui/input';
import { cn } from '@/lib/utils';
import { useId } from 'react';
interface InputFieldProps extends Omit<React.ComponentProps<typeof Input>, 'onChange' | 'id'> {
    onChange?: (value: string) => void;
    error?: string;
    label?: string;
    fieldClassName?: string;
}

export function InputField({ required, error, label, disabled, className, value, onChange, fieldClassName, ...props }: InputFieldProps) {
    const id = useId();
    return (
        <FieldContainer className={className}>
            <FieldLabel disabled={disabled} id={id} label={label} required={required} />
            <Input
                required={required}
                disabled={disabled}
                id={id}
                value={value}
                className={cn(
                    {
                        'border-red-600 ring-offset-red-600 focus-visible:ring-red-600 dark:border-red-400 dark:ring-offset-red-400 dark:focus-visible:ring-red-400':
                            error,
                    },
                    fieldClassName,
                )}
                onChange={(e) => {
                    onChange?.(e.target.value);
                }}
                {...props}
            />
            <FieldError error={error} />
        </FieldContainer>
    );
}
