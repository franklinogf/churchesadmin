import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { useId } from 'react';

interface SwitchFieldProps {
    label: string;
    disabled?: boolean;
    value?: boolean;
    onChange?: (checked: boolean) => void;
}
export function SwitchField({ label, disabled, value, onChange }: SwitchFieldProps) {
    const id = useId();
    return (
        <div className="flex items-center space-x-2">
            <Switch name={id} disabled={disabled} checked={value} onCheckedChange={onChange} id={id} />
            <Label htmlFor={id}>{label}</Label>
        </div>
    );
}
