import { useLaravelReactI18n } from 'laravel-react-i18n';
import type enJson from '../../../lang/en.json';
import type enPhp from '../../../lang/php_en.json';

type TranslationKey = keyof typeof enJson;
type PHPTranslationKey = keyof typeof enPhp;

type TranslationKeyWithPHP = TranslationKey | PHPTranslationKey;

export function useTranslations<T = TranslationKeyWithPHP>() {
  const { t, tChoice, currentLocale, setLocale } = useLaravelReactI18n<T>();

  return {
    t,
    tChoice,
    currentLocale,
    setLocale,
  };
}
