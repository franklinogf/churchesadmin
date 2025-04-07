import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectSeparator, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Translations, useTranslations } from '@/hooks/translations';
import { cn } from '@/lib/utils';
import React, { useId } from 'react';
export type SelectItemType = { value: string; label: string };

interface DefaultSelectFieldProps {
    required?: boolean;
    error?: string;
    label?: string;
    disabled?: boolean;
    className?: string;
    placeholder?: string;
    clearable?: boolean;
    value?: string;
    onChange?: (value: string) => void;
}

type SelectFieldPropsWithItems = DefaultSelectFieldProps & {
    items: SelectItemType[];
    children?: never;
};
type SelectFieldPropsWithChildren = DefaultSelectFieldProps & {
    items?: never;
    children: React.ReactNode;
};
type SelectFieldProps = SelectFieldPropsWithItems | SelectFieldPropsWithChildren;

export function SelectField({
    error,
    label,
    disabled,
    className,
    placeholder,
    items,
    children,
    value,
    clearable = false,
    onChange,
    required,
}: SelectFieldProps) {
    const { t } = useTranslations();
    const id = useId();
    return (
        <FieldContainer className={className}>
            <FieldLabel disabled={disabled} error={error} id={id} label={label} />
            <Select required={required} name={id} disabled={disabled} value={value} onValueChange={onChange}>
                <SelectTrigger
                    id={id}
                    className={cn('bg-input w-full', {
                        'border-destructive ring-offset-destructive focus-visible:ring-destructive': error,
                    })}
                >
                    <SelectValue placeholder={placeholder} />
                </SelectTrigger>
                <SelectContent>
                    {items
                        ? items.map((item) => (
                              <SelectItem key={item.value} value={item.value.toString()}>
                                  {t(item.label as Translations)}
                              </SelectItem>
                          ))
                        : children}
                    {clearable && (
                        <>
                            <SelectSeparator />
                            <Button
                                size="sm"
                                onClick={() => {
                                    onChange && onChange('');
                                }}
                                className="w-full"
                                variant="secondary"
                            >
                                {t('Deseleccionar')}
                            </Button>
                        </>
                    )}
                </SelectContent>
            </Select>
            <FieldError error={error} />
        </FieldContainer>
    );
}
