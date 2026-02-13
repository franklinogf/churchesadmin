import '@tanstack/react-table';

declare module '@tanstack/react-table' {
  //allows us to define custom properties for our columns
  interface ColumnMeta {
    filterVariant?: 'text' | 'range' | 'select';
    translationPrefix?: string;
  }
}
