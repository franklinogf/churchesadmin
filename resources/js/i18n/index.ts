import i18n from 'i18next';
import LanguageDetector from 'i18next-browser-languagedetector';
import { initReactI18next } from 'react-i18next';
import enAuth from './lang/en/auth';
import enCommon from './lang/en/common';
import enDatatable from './lang/en/datatable';
import enEnum from './lang/en/enum';
import enError from './lang/en/error';
import enPages from './lang/en/pages';
import enSettings from './lang/en/settings';
import esAuth from './lang/es/auth';
import esCommon from './lang/es/common';
import esDatatable from './lang/es/datatable';
import esEnum from './lang/es/enum';
import esError from './lang/es/error';
import esPages from './lang/es/pages';
import esSettings from './lang/es/settings';

export const defaultNS = 'common';
export const resources = {
  en: {
    auth: enAuth,
    common: enCommon,
    datatable: enDatatable,
    enum: enEnum,
    error: enError,
    pages: enPages,
    settings: enSettings,
  },
  es: {
    auth: esAuth,
    common: esCommon,
    datatable: esDatatable,
    enum: esEnum,
    error: esError,
    pages: esPages,
    settings: esSettings,
  },
};

export const initPromise = i18n
  .use(initReactI18next)
  .use(LanguageDetector)
  .init({
    detection: {
      order: ['htmlTag'],
      caches: [],
    },
    supportedLngs: ['en', 'es'],
    fallbackLng: 'en',
    debug: false,
    defaultNS,
    resources,
    interpolation: {
      escapeValue: false,
    },
  });

export default i18n;
