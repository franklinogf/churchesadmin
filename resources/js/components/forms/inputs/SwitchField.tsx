import { Switch } from '@/components/ui/switch';
import { useId } from 'react';
import { FieldContainer } from './FieldContainer';
import { FieldError } from './FieldError';
import { FieldLabel } from './FieldLabel';

interface SwitchFieldProps {
    label: string;
    disabled?: boolean;
    value?: boolean;
    error?: string;
    description?: string;
    onChange?: (checked: boolean) => void;
}
export function SwitchField({ label, disabled, value, error, description, onChange }: SwitchFieldProps) {
    const id = useId();
    return (
        <FieldContainer>
            <div className="space-y-0.5">
                <div className="flex items-center space-x-2">
                    <Switch
                        aria-describedby={description ? `${id}-description` : undefined}
                        name={id}
                        disabled={disabled}
                        checked={value}
                        onCheckedChange={onChange}
                        id={id}
                    />
                    <FieldLabel disabled={disabled} id={id} label={label} />
                </div>
                {description && (
                    <p className="text-muted-foreground text-sm" id={`${id}-description`}>
                        {description}
                    </p>
                )}
            </div>
            <FieldError error={error} />
        </FieldContainer>
    );
}
