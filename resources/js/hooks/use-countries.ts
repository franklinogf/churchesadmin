import { getNames, registerLocale } from 'i18n-iso-countries';
import * as en from 'i18n-iso-countries/langs/en.json';
import * as es from 'i18n-iso-countries/langs/es.json';
import { useMemo } from 'react';
import { useTranslation } from 'react-i18next';

registerLocale(es);
registerLocale(en);

export function useCountries() {
  const { i18n } = useTranslation();

  const countries = useMemo(() => {
    return Object.entries(getNames(i18n.language)).map(([code, name]) => ({
      code: code.toUpperCase(),
      name,
    }));
  }, [i18n.language]);

  function getCurrentCountryName(code: string) {
    return countries.find((country) => country.code.toUpperCase() === code.toUpperCase())?.name || code.toUpperCase();
  }

  return { countries, getCurrentCountryName };
}
