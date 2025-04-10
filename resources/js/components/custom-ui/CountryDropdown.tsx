import { Ref, useCallback, useEffect, useState } from 'react';

// shadcn
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';

// utils
import { cn } from '@/lib/utils';

// assets
import { useCountries } from '@/hooks/use-countries';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { CheckIcon, ChevronDown, XSquare } from 'lucide-react';
import { Button } from '../ui/button';

// Dropdown props
interface CountryDropdownProps {
    onChange?: (country: string) => void;
    defaultValue?: string;
    disabled?: boolean;
    placeholder?: string;
    ref?: Ref<HTMLButtonElement>;
}

export function CountryDropdown({ onChange, defaultValue, disabled = false, placeholder, ref }: CountryDropdownProps) {
    const { countries, getCurrentCountryName } = useCountries();
    const [open, setOpen] = useState(false);
    const [selectedCountry, setSelectedCountry] = useState('');
    const { t } = useLaravelReactI18n();

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
            setSelectedCountry(countryCode);
            onChange?.(countryCode);
            setOpen(false);
        },
        [onChange],
    );

    const triggerClasses = cn(
        'border-input ring-offset-background placeholder:text-muted-foreground focus:ring-ring flex h-9 w-full items-center justify-between rounded-md border bg-transparent px-3 py-2 text-sm whitespace-nowrap shadow-sm focus:ring-1 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50 [&>span]:line-clamp-1',
    );

    return (
        <Popover open={open} onOpenChange={setOpen}>
            <PopoverTrigger ref={ref} className={triggerClasses} disabled={disabled}>
                {selectedCountry ? (
                    <div className="flex flex-grow items-center gap-2 overflow-hidden">
                        <span className="overflow-hidden text-ellipsis whitespace-nowrap">{getCurrentCountryName(selectedCountry)}</span>
                        <Button
                            className="hover:text-primary size-4 cursor-pointer"
                            size="sm"
                            variant="ghost"
                            onClick={(e) => {
                                e.stopPropagation();
                                handleSelect('');
                            }}
                        >
                            <XSquare />
                        </Button>
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
                                <CommandItem
                                    className="flex w-full items-center gap-2"
                                    key={country.code}
                                    onSelect={() => handleSelect(country.code)}
                                >
                                    <span className="overflow-hidden text-ellipsis whitespace-nowrap">{country.name}</span>
                                    <CheckIcon
                                        className={cn('ml-auto h-4 w-4 shrink-0', country.code === selectedCountry ? 'opacity-100' : 'opacity-0')}
                                    />
                                </CommandItem>
                            ))}
                        </CommandGroup>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>
    );
}
