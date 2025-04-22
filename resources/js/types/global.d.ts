import type ContextInterface from 'laravel-react-i18n/dist/interfaces/context';
import type { route as routeFn } from 'ziggy-js';
import type { AutoComplete } from './generics';

import type translations from '../../../lang/en.json';
import type phpTranslations from '../../../lang/php_en.json';

type TranslationKey = keyof typeof translations;
type PhpTranslationKey = keyof typeof phpTranslations;
declare global {
  const route: typeof routeFn;
}

declare module 'laravel-react-i18n' {
  export function useLaravelReactI18n(): ContextInterface<AutoComplete<TranslationKey | PhpTranslationKey>>;
}
