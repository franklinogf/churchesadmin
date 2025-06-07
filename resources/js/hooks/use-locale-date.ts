import { useTranslations } from '@/hooks/use-translations';
import { intlFormat, parseISO } from 'date-fns';
import { enUS, es, type Locale } from 'date-fns/locale';
import { useUser } from './use-user';

export function useLocaleDate() {
  const { currentLocale } = useTranslations();
  const { user } = useUser();

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

  function maxDate() {
    return new Date(user.currentYear, 11, 31); // December 31st of the current year
  }

  return {
    getCurrentDateLocale,
    formatLocaleDate,
    maxDate,
  };
}
