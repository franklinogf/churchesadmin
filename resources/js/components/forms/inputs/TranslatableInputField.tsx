import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { Input } from '@/components/ui/input';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { LanguageTranslations, SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import React from 'react';
import { FieldLabel } from './FieldLabel';

interface TranslatableInputProps {
    id?: string;
    label: string;
    errors?: { errors: Record<any, string>; name: string };
    values: LanguageTranslations;
    disabled?: boolean;
    onChange: (locale: string, value: string) => void;
}

export default function TranslatableInput({ id, label, errors, values, disabled, onChange }: TranslatableInputProps) {
    const locales = usePage<SharedData>().props.availableLocales;
    const [activeLocale, setActiveLocale] = React.useState(locales[0].value);
    const { t } = useLaravelReactI18n();

    const errorMessage = errors?.errors[`${errors.name}.${activeLocale}`];
    const localeError = (locale: string) => Object.keys(errors?.errors ?? {}).some((key) => key.endsWith(locale));
    const isError = Object.keys(errors?.errors ?? {}).some((key) => key.startsWith(errors?.name ?? ''));

    return (
        <FieldContainer className="space-y-2">
            <FieldLabel disabled={disabled} error={isError} id={id} label={label} />
            <Tabs value={activeLocale} onValueChange={(val) => setActiveLocale(val)}>
                <TabsList className="gap-0.5">
                    {locales.map(({ value, label }) => (
                        <TabsTrigger
                            className={localeError(value) ? 'bg-destructive/20 data-[state=active]:bg-destructive/50' : ''}
                            key={value}
                            value={value}
                        >
                            {label}
                        </TabsTrigger>
                    ))}
                </TabsList>
                {locales.map(({ value: code, label: langLabel }) => (
                    <TabsContent key={code} value={code}>
                        <Input
                            name={`${id}[${code}]`}
                            value={values[code as keyof LanguageTranslations]}
                            onChange={(e) => onChange(code, e.target.value)}
                            placeholder={t(`Enter :name in :langLabel`, {
                                name: label.toLowerCase(),
                                langLabel,
                            })}
                        />
                    </TabsContent>
                ))}
            </Tabs>
            <FieldError error={errorMessage} />
        </FieldContainer>
    );
}
