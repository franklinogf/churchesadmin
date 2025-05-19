import { useTranslations } from '@/hooks/use-translations';
import { getNames, registerLocale } from 'i18n-iso-countries';
import * as en from 'i18n-iso-countries/langs/en.json';
import * as es from 'i18n-iso-countries/langs/es.json';
import { useMemo } from 'react';

registerLocale(es);
registerLocale(en);

export function useCountries() {
  const { currentLocale } = useTranslations();

  const countries = useMemo(() => {
    return Object.entries(getNames(currentLocale())).map(([code, name]) => ({
      code: code.toUpperCase(),
      name,
    }));
  }, [currentLocale]);

  function getCurrentCountryName(code: string) {
    return countries.find((country) => country.code.toUpperCase() === code.toUpperCase())?.name || code.toUpperCase();
  }

  return { countries, getCurrentCountryName };
}
