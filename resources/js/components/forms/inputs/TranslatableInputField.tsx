import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';
import { Input } from '@/components/ui/input';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { type LanguageTranslations, type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import React, { useId } from 'react';

interface TranslatableInputProps {
  label: string;
  errors?: { errors: Record<string, string>; name: string };
  values: LanguageTranslations;
  disabled?: boolean;
  onChange: (locale: string, value: string) => void;
  required?: boolean;
}

export function TranslatableInput({ label, errors, values, disabled, required, onChange }: TranslatableInputProps) {
  const locales = usePage<SharedData>().props.availableLocales;
  const [activeLocale, setActiveLocale] = React.useState(locales[0]?.value);
  const { t } = useLaravelReactI18n();

  const errorMessage = errors?.errors[`${errors.name}.${activeLocale}`];
  const localeError = (locale: string) => Object.keys(errors?.errors ?? {}).some((key) => key.endsWith(locale));

  const id = useId();
  return (
    <FieldContainer className="space-y-2">
      <FieldLabel disabled={disabled} id={id} label={label} required={required} />
      <Tabs value={activeLocale} onValueChange={(val) => setActiveLocale(val)}>
        <TabsList className="gap-0.5">
          {locales.map(({ value, label }) => (
            <TabsTrigger className={localeError(value) ? 'bg-destructive/20 data-[state=active]:bg-destructive/50' : ''} key={value} value={value}>
              {label}
            </TabsTrigger>
          ))}
        </TabsList>
        {locales.map(({ value: code, label: langLabel }) => (
          <TabsContent key={code} value={code}>
            <Input
              id={id}
              required={required}
              name={`${id}[${code}]`}
              value={values[code as keyof LanguageTranslations]}
              onChange={(e) => onChange(code, e.target.value)}
              placeholder={t(`Enter :name in :Language`, {
                name: label.toLowerCase(),
                language: langLabel,
              })}
            />
          </TabsContent>
        ))}
      </Tabs>
      <FieldError error={errorMessage} />
    </FieldContainer>
  );
}
