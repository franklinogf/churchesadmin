import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { cn } from '@/lib/utils';
import { colorOptions } from '../../calendar/calendar-tailwind-classes';

interface ColorPickerProps<TValue = string> {
  value?: TValue;
  onChange?: (value: TValue) => void;
}

export function ColorPicker<TValue>({ value, onChange }: ColorPickerProps<TValue>) {
  return (
    <RadioGroup onValueChange={(value) => onChange && onChange(value as unknown as TValue)} defaultValue={value as string} className="flex gap-2">
      {colorOptions.map((color) => (
        <RadioGroupItem
          key={color.value}
          value={color.value}
          id={color.value}
          className={cn('size-6 border-0 shadow-none transition-all duration-200', color.class.picker)}
          aria-label={color.label}
        />
      ))}
    </RadioGroup>
  );
}
