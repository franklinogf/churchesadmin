import { useTranslations } from '@/hooks/use-translations';
import { intlFormat, parseISO } from 'date-fns';
import { enUS, es, type Locale } from 'date-fns/locale';

export function useLocaleDate() {
  const { currentLocale } = useTranslations();

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
