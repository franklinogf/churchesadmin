import { type Ref, useCallback, useEffect, useState } from 'react';

// shadcn
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';

// utils
import { cn } from '@/lib/utils';

// assets
import { useCountries } from '@/hooks/use-countries';
import { useTranslations } from '@/hooks/use-translations';
import { CheckIcon, ChevronDown, XSquare } from 'lucide-react';
import { Button } from '../ui/button';

// Dropdown props
interface CountryDropdownProps {
  onChange?: (country: string) => void;
  defaultValue?: string;
  disabled?: boolean;
  placeholder?: string;
  clearable?: boolean;
  ref?: Ref<HTMLButtonElement>;
}

export function CountryDropdown({ onChange, defaultValue, disabled = false, placeholder, clearable = false, ref }: CountryDropdownProps) {
  const { countries, getCurrentCountryName } = useCountries();
  const [open, setOpen] = useState(false);
  const [selectedCountry, setSelectedCountry] = useState('');
  const { t } = useTranslations();

  useEffect(() => {
    if (defaultValue) {
      const initialCountry = countries.find((country) => country.code === defaultValue);
      if (initialCountry) {
        setSelectedCountry(initialCountry.code);
      } else {
        // Reset selected country if defaultValue is not found
        setSelectedCountry('');
      }
    } else {
      // Reset selected country if defaultValue is null or undefined
      setSelectedCountry('');
    }
  }, [defaultValue, countries]);

  const handleSelect = useCallback(
    (countryCode: string) => {
      const country = countryCode === selectedCountry ? '' : countryCode; // Toggle selection
      setSelectedCountry(country);
      onChange?.(country);
      setOpen(false);
    },
    [onChange, selectedCountry],
  );

  const triggerClasses = cn(
    'bg-input/20 border-input ring-offset-background placeholder:text-muted-foreground focus:ring-ring flex h-9 w-full items-center justify-between rounded-md border px-3 py-2 text-sm whitespace-nowrap shadow-sm focus:ring-1 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50 [&>span]:line-clamp-1',
  );

  return (
    <Popover open={open} onOpenChange={setOpen}>
      <PopoverTrigger ref={ref} className={triggerClasses} disabled={disabled}>
        {selectedCountry ? (
          <div className="flex flex-grow items-center gap-2 overflow-hidden">
            <span className="overflow-hidden text-ellipsis whitespace-nowrap">{getCurrentCountryName(selectedCountry)}</span>
            {clearable && (
              <Button
                asChild
                className="hover:text-primary size-4 cursor-pointer"
                size="sm"
                variant="link"
                onClick={(e) => {
                  e.stopPropagation();
                  handleSelect('');
                }}
              >
                <span>
                  <XSquare />
                </span>
              </Button>
            )}
          </div>
        ) : (
          <span>{placeholder}</span>
        )}
        <ChevronDown size={16} />
      </PopoverTrigger>
      <PopoverContent collisionPadding={10} side="bottom" className="min-w-[--radix-popper-anchor-width] p-0">
        <Command className="max-h-[200px] w-full sm:max-h-[270px]">
          <CommandList>
            <div className="bg-popover sticky top-0 z-10">
              <CommandInput placeholder={t('Search for a country...')} />
            </div>
            <CommandEmpty>{t('No results found.')}</CommandEmpty>
            <CommandGroup>
              {countries.map((country) => (
                <CommandItem className="flex w-full items-center gap-2" key={country.code} onSelect={() => handleSelect(country.code)}>
                  <span className="overflow-hidden text-ellipsis whitespace-nowrap">{country.name}</span>
                  <CheckIcon className={cn('ml-auto h-4 w-4 shrink-0', country.code === selectedCountry ? 'opacity-100' : 'opacity-0')} />
                </CommandItem>
              ))}
            </CommandGroup>
          </CommandList>
        </Command>
      </PopoverContent>
    </Popover>
  );
}
