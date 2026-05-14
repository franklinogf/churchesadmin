import type { RowData } from '@tanstack/react-table';

export type DatatableFilterVariant = 'text' | 'range' | 'select';

declare module '@tanstack/react-table' {
  //allows us to define custom properties for our columns
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  interface ColumnMeta<TData extends RowData, TValue> {
    filter?: { variant: Exclude<DatatableFilterVariant, 'select'> } | { variant: 'select'; options?: { value: string; label: string }[] };
    translationPrefix?: string;
    label?: string;
  }
}
