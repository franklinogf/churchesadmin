import { intlFormat, parseISO } from 'date-fns';
import { enUS, es, type Locale } from 'date-fns/locale';
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

  function formatLocaleDate(
    date: string | Date = new Date(),
    { dateStyle }: { dateStyle: Intl.DateTimeFormatOptions['dateStyle'] } = {
      dateStyle: 'medium',
    },
  ) {
    return intlFormat(typeof date === 'string' ? parseISO(date) : date, { dateStyle }, { locale: currentLocale() });
  }

  return {
    getCurrentDateLocale,
    formatLocaleDate,
  };
}
