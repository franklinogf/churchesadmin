import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';
import { RequiredFieldIcon } from '../RequiredFieldIcon';

export function FieldLabel({
    required,
    label,
    disabled,
    id,
    className,
}: {
    required?: boolean;
    label?: string;
    disabled?: boolean;
    id?: string;
    className?: string;
}) {
    if (!label) return null;

    return (
        <Label
            asChild={id === undefined}
            aria-disabled={disabled}
            className={cn(
                'flex gap-x-0.5',
                {
                    'text-muted-foreground/80': disabled,
                },
                className,
            )}
            htmlFor={id}
        >
            {id === undefined ? (
                <div>
                    {label}
                    {required && <RequiredFieldIcon />}
                </div>
            ) : (
                <>
                    <span>{label}</span>
                    {required && <RequiredFieldIcon />}
                </>
            )}
        </Label>
    );
}
