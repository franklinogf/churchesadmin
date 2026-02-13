import { Button } from '@/components/ui/button';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { Select, SelectContent, SelectItem, SelectSeparator, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useTranslations } from '@/hooks/use-translations';
import { cn } from '@/lib/utils';
import { type InputBaseProps, type SelectOption } from '@/types';
import React, { useId, type ComponentProps } from 'react';

type DefaultSelectFieldProps = InputBaseProps &
  ComponentProps<typeof Select> & {
    clearable?: boolean;
    placeholder?: string;
    className?: string;
  };

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
  clearable = false,
  required,
  onValueChange,
  ...props
}: SelectFieldProps) {
  const { t } = useTranslations();
  const id = useId();
  return (
    <Field data-disabled={disabled} data-invalid={!!error} className={className}>
      <FieldLabel htmlFor={id}>{label}</FieldLabel>
      <Select required={required} disabled={disabled} {...props} onValueChange={onValueChange}>
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
                  onValueChange?.('');
                }}
                className="w-full"
                variant="secondary"
              >
                {t('Deselect')}
              </Button>
            </>
          )}
        </SelectContent>
      </Select>
      <FieldError>{error}</FieldError>
    </Field>
  );
}
