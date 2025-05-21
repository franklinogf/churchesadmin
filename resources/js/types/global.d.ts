import type { route as routeFn } from 'ziggy-js';

declare global {
  const route: typeof routeFn;
}

declare module '@tanstack/react-table' {
  //allows us to define custom properties for our columns
  interface ColumnMeta {
    filterVariant?: 'text' | 'range' | 'select';
    translationPrefix?: string;
  }
}
