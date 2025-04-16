import { intlFormat } from 'date-fns';
import { enUS, es, Locale } from 'date-fns/locale';
import { useLaravelReactI18n } from 'laravel-react-i18n';

export function useLocaleDate() {
  const { currentLocale } = useLaravelReactI18n();

  function getCurrentDateLocale() {
    const dateLocales: Record<string, Locale> = {
      es: es,
      en: enUS,
    };
    return dateLocales[currentLocale()] || enUS;
  }

  function formatDate(
    date: string | Date = new Date(),
    { dateStyle }: { dateStyle: Intl.DateTimeFormatOptions['dateStyle'] } = {
      dateStyle: 'medium',
    },
  ) {
    if (!date) return undefined;
    return intlFormat(date, { dateStyle }, { locale: currentLocale() });
  }

  return {
    getCurrentDateLocale,
    formatDate,
  };
}
