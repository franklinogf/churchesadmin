import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';
import { useTranslations } from '@/hooks/use-translations';
import { type SelectOption } from '@/types';
import { Check, ChevronsUpDown } from 'lucide-react';
import { useState } from 'react';

import { Button } from '@/components/ui/button';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { cn } from '@/lib/utils';

interface ComboboxFieldProps {
  required?: boolean;
  error?: string;
  label?: string;
  disabled?: boolean;
  className?: string;
  placeholder?: string;
  value: string;
  onChange: (value: string) => void;
  options: SelectOption[];
}

export function ComboboxField({ error, label, disabled, className, placeholder, options, value, onChange, required }: ComboboxFieldProps) {
  const [open, setOpen] = useState(false);
  const { t } = useTranslations();
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
            className={cn('bg-input/20 w-full justify-between', {
              'border-destructive ring-offset-destructive focus-visible:ring-destructive': error,
            })}
            disabled={disabled}
          >
            {value ? options.find((option) => option.value.toString() === value)?.label : placeholder}
            <ChevronsUpDown className="ml-auto shrink-0 opacity-50" />
          </Button>
        </PopoverTrigger>
        <PopoverContent className="w-full max-w-fit p-0">
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
              <CommandGroup>
                {options.map((option) => (
                  <CommandItem
                    keywords={[option.label]}
                    key={option.value}
                    value={option.value.toString()}
                    onSelect={(currentValue) => {
                      onChange(currentValue === value ? '' : currentValue);
                      setOpen(false);
                    }}
                  >
                    {option.label}
                    <Check className={cn('ml-auto', value === option.value.toString() ? 'opacity-100' : 'opacity-0')} />
                  </CommandItem>
                ))}
              </CommandGroup>
            </CommandList>
          </Command>
        </PopoverContent>
      </Popover>
      <FieldError error={error} />
    </FieldContainer>
  );
}
