import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { Input } from '@/components/ui/input';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { LanguageTranslations, SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import React, { useEffect, useId } from 'react';
import { FieldLabel } from './FieldLabel';

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
  const [activeLocale, setActiveLocale] = React.useState(locales[0].value);
  const { t } = useLaravelReactI18n();

  const errorMessage = errors?.errors[`${errors.name}.${activeLocale}`];
  const localeError = (locale: string) => Object.keys(errors?.errors ?? {}).some((key) => key.endsWith(locale));

  useEffect(() => {
    const tabEvent = (e: KeyboardEvent) => {
      if (e.key === 'Tab') {
        const currentIndex = locales.findIndex((locale) => locale.value === activeLocale);
        const nextIndex = (currentIndex + 1) % locales.length;
        setActiveLocale(locales[nextIndex].value);
      }
    };
    document.addEventListener('keydown', tabEvent);

    return () => {
      document.removeEventListener('keydown', tabEvent);
    };
  }, [locales, activeLocale]);
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
