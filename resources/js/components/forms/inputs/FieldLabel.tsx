import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';

export function FieldLabel({
    label,
    error,
    disabled,
    id,
    className,
}: {
    label?: string;
    error?: string;
    disabled?: boolean;
    id?: string;
    className?: string;
}) {
    if (!label) return null;

    return (
        <Label
            className={cn(className, {
                'text-destructive': error,
                'text-muted-foreground/80': disabled,
            })}
            htmlFor={id}
        >
            {label}
        </Label>
    );
}
