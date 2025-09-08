import type { TranslationKey } from '@/types/lang-keys';
import { useLaravelReactI18n } from 'laravel-react-i18n';

export function useTranslations() {
  const { t, tChoice, currentLocale, setLocale } = useLaravelReactI18n<TranslationKey>();

  return {
    t,
    tChoice,
    currentLocale,
    setLocale,
  };
}
