import { type LanguageTranslations, type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';

export function useTranslations() {
  const {
    props: { availableLocales },
  } = usePage<SharedData>();

  const emptyTranslations = availableLocales.reduce((acc, { value }) => {
    acc[value as keyof LanguageTranslations] = '';
    return acc;
  }, {} as LanguageTranslations);

  return { emptyTranslations };
}
