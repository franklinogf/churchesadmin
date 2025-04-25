import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';
import { type SelectOptionWithModel } from '@/types';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { Check, ChevronsUpDown } from 'lucide-react';
import { useState } from 'react';

import { Button } from '@/components/ui/button';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { cn } from '@/lib/utils';

interface MultipleComboboxFieldProps {
  required?: boolean;
  error?: string;
  label?: string;
  disabled?: boolean;
  className?: string;
  placeholder?: string;
  value: { id: string; model: string };
  onChange: (value: { id: string; model: string }) => void;
  data: SelectOptionWithModel[];
}

export function MultipleComboboxField({
  error,
  label,
  disabled,
  className,
  placeholder,
  data,
  value,
  onChange,
  required,
}: MultipleComboboxFieldProps) {
  const [open, setOpen] = useState(false);

  const { t } = useLaravelReactI18n();
  placeholder = placeholder ?? t('Select an option');
  return (
    <FieldContainer className={className}>
      <FieldLabel disabled={disabled} label={label} required={required} />
      <Popover open={open} onOpenChange={setOpen}>
        <PopoverTrigger asChild>
          <Button
            variant="outline"
            role="combobox"
            aria-expanded={open}
            className={cn('w-full justify-between', { 'border-destructive ring-offset-destructive focus-visible:ring-destructive': error })}
            disabled={disabled}
          >
            {/* {value ? options.find((option) => option.value.toString() === value)?.label : placeholder} */}

            {value
              ? data.find((option) => option.model === value.model)?.options.find((opt) => opt.value.toString() === value.id)?.label
              : placeholder}

            <ChevronsUpDown className="ml-auto shrink-0 opacity-50" />
          </Button>
        </PopoverTrigger>
        <PopoverContent className="w-fit max-w-[300px] p-0">
          <Command
            filter={(value, search, keywords) => {
              const extendedValue = value + ' ' + keywords?.join(' ');
              if (extendedValue.toLowerCase().includes(search.toLowerCase())) return 1;
              return 0;
            }}
          >
            <CommandInput placeholder={placeholder} />
            <CommandList>
              <CommandEmpty>{t('No options found')}</CommandEmpty>

              {data.map((option) => (
                <CommandGroup key={option.heading} heading={option.heading}>
                  {option.options.map((item) => (
                    <CommandItem
                      keywords={[item.label]}
                      key={`${option.model}-${item.value}`}
                      value={`${option.model}-${item.value}`}
                      onSelect={(currentValue) => {
                        onChange(
                          currentValue === value.id
                            ? { id: '', model: '' }
                            : { id: currentValue.replace(`${option.model}-`, ''), model: option.model },
                        );
                        setOpen(false);
                      }}
                    >
                      {item.label}
                      <Check
                        className={cn('ml-auto', value.id === item.value.toString() && value.model === option.model ? 'opacity-100' : 'opacity-0')}
                      />
                    </CommandItem>
                  ))}
                </CommandGroup>
              ))}
            </CommandList>
          </Command>
        </PopoverContent>
      </Popover>
      <FieldError error={error} />
    </FieldContainer>
  );
}
