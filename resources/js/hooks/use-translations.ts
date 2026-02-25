import type { TranslationKeys } from '@/types/lang-keys';
import { useLaravelReactI18n } from 'laravel-react-i18n';

export function useTranslations() {
  const { t, tChoice, currentLocale, setLocale } = useLaravelReactI18n<TranslationKeys>();

  return {
    t,
    tChoice,
    currentLocale,
    setLocale,
  };
}
