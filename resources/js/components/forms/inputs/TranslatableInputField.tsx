import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { Input } from '@/components/ui/input';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import React from 'react';
import { FieldLabel } from './FieldLabel';

interface TranslatableInputProps {
    id?: string;
    label: string;
    error?: string;
    values?: Record<string, string>;
    disabled?: boolean;
    onChange?: (locale: string, value: string) => void;
}

export default function TranslatableInput({ id, label, error, values, disabled, onChange }: TranslatableInputProps) {
    const locales = usePage<SharedData>().props.availableLocales;
    const [activeLocale, setActiveLocale] = React.useState(locales[0].value);
    const { t, tChoice } = useLaravelReactI18n();
    console.log(tChoice('(and :count more errors)', 1, { count: 2 }));
    return (
        <FieldContainer className="space-y-2">
            <FieldLabel disabled={disabled} error={error !== undefined} id={id} label={label} />
            <Tabs value={activeLocale} onValueChange={(val) => setActiveLocale(val)}>
                <TabsList>
                    {locales.map(({ value, label }) => (
                        <TabsTrigger key={value} value={value}>
                            {label}
                        </TabsTrigger>
                    ))}
                </TabsList>
                {locales.map(({ value, label: langLabel }) => (
                    <TabsContent key={value} value={value}>
                        <Input
                            name={`${id}[${value}]`}
                            value={values?.[value]}
                            onChange={(e) => onChange?.(value, e.target.value)}
                            placeholder={t(`Enter :name in :langLabel`, {
                                name: label.toLowerCase(),
                                langLabel,
                            })}
                        />
                    </TabsContent>
                ))}
            </Tabs>
            <FieldError error={error} />
        </FieldContainer>
    );
}
