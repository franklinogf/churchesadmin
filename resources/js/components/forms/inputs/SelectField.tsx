import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectSeparator, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useTranslations } from '@/hooks/use-translations';
import { cn } from '@/lib/utils';
import { type SelectOption } from '@/types';
import React, { useId } from 'react';

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
  options: SelectOption[];
  children?: never;
};
type SelectFieldPropsWithChildren = DefaultSelectFieldProps & {
  options?: never;
  children: React.ReactNode;
};
type SelectFieldProps = SelectFieldPropsWithItems | SelectFieldPropsWithChildren;

export function SelectField({
  error,
  label,
  disabled,
  className,
  placeholder,
  options,
  children,
  value,
  clearable = false,
  onChange,
  required,
}: SelectFieldProps) {
  const { t } = useTranslations<string>();
  const id = useId();
  return (
    <FieldContainer className={className}>
      <FieldLabel disabled={disabled} id={id} label={label} required={required} />
      <Select required={required} name={id} disabled={disabled} value={value} onValueChange={onChange}>
        <SelectTrigger
          id={id}
          className={cn('w-full', {
            'border-destructive ring-offset-destructive focus-visible:ring-destructive': error,
          })}
        >
          <SelectValue placeholder={placeholder} />
        </SelectTrigger>
        <SelectContent>
          {options
            ? options.map((item) => (
                <SelectItem key={item.value} value={item.value.toString()}>
                  {item.label}
                </SelectItem>
              ))
            : children}
          {clearable && (
            <>
              <SelectSeparator />
              <Button
                size="sm"
                onClick={() => {
                  onChange?.('');
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
