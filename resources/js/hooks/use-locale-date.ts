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

  const defaultFormatOptions: { dateStyle: Intl.DateTimeFormatOptions['dateStyle'] } = { dateStyle: 'medium' };

  function formatLocaleDate(date?: string | Date, formatOptions: { dateStyle: Intl.DateTimeFormatOptions['dateStyle'] } = defaultFormatOptions) {
    const targetDate = date ?? new Date();
    const parsedDate = typeof targetDate === 'string' ? parseISO(targetDate) : targetDate;

    return intlFormat(parsedDate, { dateStyle: formatOptions.dateStyle }, { locale: currentLocale() });
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
