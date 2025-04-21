import type ContextInterface from 'laravel-react-i18n/dist/interfaces/context';
import type { route as routeFn } from 'ziggy-js';
import type { AutoComplete } from './generics';

declare global {
  const route: typeof routeFn;
}

declare module 'laravel-react-i18n' {
  type TranslationKey = keyof typeof import('../../../lang/en.json');
  type PhpTranslationKey = keyof typeof import('../../../lang/php_en.json');
  declare function useLaravelReactI18n(): ContextInterface<AutoComplete<TranslationKey | PhpTranslationKey>>;
}
