import { formatDatetime, formatRelativeDate } from '@/lib/utils';
import type { EnumColumn } from '@/types/models';
import type { ColumnDef } from '@tanstack/react-table';
import type { ComponentProps } from 'react';
import { formatPhoneNumber } from 'react-phone-number-input';
import { DatatableCell, DatatableCellBadge, DatatableCellBoolean } from './datatable-cell';
import type { DatatableCellProps } from './datatable-cell-actions';
import { DatatableCellActions } from './datatable-cell-actions';
import { DatatableHeader } from './datatable-header';

type DotPrefix<T extends string> = T extends '' ? '' : `.${T}`;

type DeepKeys<T, Match = unknown> = T extends object
  ? {
      [K in keyof T & string]: NonNullable<T[K]> extends infer V
        ? V extends any[]
          ? Match extends unknown
            ? `${K}` // keep default behavior
            : never
          : V extends object
            ? // include current key if it matches
                | (Extract<V, Match> extends never ? never : `${K}`)
                // recurse
                | `${K}${DotPrefix<DeepKeys<V, Match>>}`
            : Extract<V, Match> extends never
              ? never
              : `${K}`
        : never;
    }[keyof T & string]
  : never;

type ColumnOptions<TData> = Omit<ColumnDef<TData>, 'accessorKey' | 'header' | 'id' | 'cell'>;

export function defaultColumnOptions<TData>(options?: ColumnOptions<TData>): ColumnOptions<TData> {
  return {
    enableHiding: options?.enableHiding ?? false,
    ...options,
  };
}

export function createTextColumn<TData extends object>(
  id: DeepKeys<TData, string>,
  headerLabel: string,
  options?: ColumnOptions<TData> & {
    align?: ComponentProps<typeof DatatableHeader>['align'];
  },
): ColumnDef<TData> {
  return {
    id: String(id),
    accessorKey: id,
    header: ({ column }) => <DatatableHeader column={column} align={options?.align} title={headerLabel} />,
    cell: ({ getValue }) => {
      const value = getValue() as string;

      if (!value) {
        return null;
      }

      return <DatatableCell align={options?.align}>{value}</DatatableCell>;
    },
    ...defaultColumnOptions(options),
    meta: { ...options?.meta, label: headerLabel },
  };
}
export function createDatetimeColumn<TData extends object>(
  id: DeepKeys<TData, string>,
  headerLabel: string,
  options?: ColumnOptions<TData> & { format?: 'default' | 'relative' },
): ColumnDef<TData> {
  return {
    id: String(id),
    accessorKey: id,
    header: ({ column }) => <DatatableHeader align="center" column={column} title={headerLabel} />,
    cell: ({ getValue }) => {
      const value = getValue() as string;

      if (!value) {
        return null;
      }

      return <DatatableCell align="center">{options?.format === 'relative' ? formatRelativeDate(value) : formatDatetime(value)}</DatatableCell>;
    },
    ...defaultColumnOptions(options),
    meta: { ...options?.meta, label: headerLabel },
  };
}

export function createPhoneColumn<TData extends object>(
  id: DeepKeys<TData, string>,
  headerLabel: string,
  options?: ColumnOptions<TData>,
): ColumnDef<TData> {
  return {
    id: String(id),
    accessorKey: id,
    header: ({ column }) => <DatatableHeader align="center" column={column} title={headerLabel} />,
    cell: ({ getValue }) => {
      const value = getValue() as string;

      if (!value) {
        return null;
      }

      return <DatatableCell align="center">{formatPhoneNumber(value)}</DatatableCell>;
    },
    ...defaultColumnOptions(options),
    meta: { ...options?.meta, label: headerLabel },
  };
}

export function createActionsColumn<TData extends object>(actions: DatatableCellProps | ((row: TData) => DatatableCellProps)): ColumnDef<TData> {
  return {
    id: 'actions',
    header: () => null,
    cell: ({ row }) => {
      return <DatatableCellActions {...(typeof actions === 'function' ? actions(row.original) : actions)} />;
    },
    enableColumnFilter: false,
    enableSorting: false,
    enableHiding: false,
  };
}

export function createBadgeColumn<TData extends object>(
  id: DeepKeys<TData, string> | keyof TData,
  headerLabel: string,
  options?: ColumnOptions<TData> & {
    colors?: Record<string, { color: string }>;
  },
): ColumnDef<TData> {
  return {
    id: String(id),
    accessorKey: id,
    header: ({ column }) => <DatatableHeader align="center" column={column} title={headerLabel} />,
    cell: ({ getValue }) => {
      const value = getValue() as EnumColumn<string> | string | undefined;

      if (!value) {
        return null;
      }

      const displayValue = typeof value === 'string' ? value : value.label;
      const css = typeof value === 'string' ? undefined : options?.colors?.[value.value]?.color;

      return <DatatableCellBadge className={css}>{displayValue}</DatatableCellBadge>;
    },
    ...defaultColumnOptions(options),
    meta: { ...options?.meta, label: headerLabel },
  };
}

export function createBooleanColumn<TData extends object, TId extends DeepKeys<TData, string> | keyof TData>(
  id: TId,
  headerLabel: string,
  trueValue: (value: TData[TId]) => boolean,
  options?: ColumnOptions<TData>,
): ColumnDef<TData> {
  return {
    id: String(id),
    accessorKey: id,
    header: ({ column }) => <DatatableHeader align="center" column={column} title={headerLabel} />,
    cell: ({ getValue }) => {
      const value = getValue() as TData[TId];

      return <DatatableCellBoolean value={trueValue(value)} />;
    },
    ...defaultColumnOptions(options),
    meta: { ...options?.meta, label: headerLabel },
  };
}
