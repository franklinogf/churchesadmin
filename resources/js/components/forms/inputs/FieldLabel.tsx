import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';

export function FieldLabel({ label, disabled, id, className }: { label?: string; disabled?: boolean; id?: string; className?: string }) {
    if (!label) return null;

    return (
        <Label
            asChild={id === undefined}
            aria-disabled={disabled}
            className={cn(className, {
                'text-muted-foreground/80': disabled,
            })}
            htmlFor={id}
        >
            {id === undefined ? <p>{label}</p> : label}
        </Label>
    );
}
