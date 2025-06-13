import type { PdfColumn, SelectOption } from '@/types';
import { createContext, type ReactNode, useContext, useEffect, useMemo, useState } from 'react';

// Constants for PDF options
const orientations = ['Portrait', 'Landscape'] as const;
const formats = ['letter', 'legal', 'tabloid', 'a0', 'a1', 'a2', 'a3', 'a4', 'a5', 'a6'] as const;

export const formatOptions: SelectOption[] = formats.map((format) => ({
  label: format.charAt(0).toUpperCase() + format.slice(1),
  value: format,
}));

export const orientationOptions: SelectOption[] = orientations.map((orientation) => ({
  label: orientation.charAt(0).toUpperCase() + orientation.slice(1),
  value: orientation,
}));

export type PdfOrientation = (typeof orientations)[number];
export type PdfFormat = (typeof formats)[number];

interface PdfGeneratorContextProps {
  columns: PdfColumn[];
  routeName: string;
  format: PdfFormat;
  setFormat: (format: PdfFormat) => void;
  orientation: PdfOrientation;
  setOrientation: (orientation: PdfOrientation) => void;
  rows: string[];
  setRows: (rows: string[]) => void;
  isColumnSelected: (columnName: string) => boolean;
  toggleColumnSelection: (columnName: string, isSelected: boolean) => void;
  isLoading: boolean;
  routeSrc: string;
  formatOptions: SelectOption[];
  orientationOptions: SelectOption[];
  setIsLoading: (loading: boolean) => void;
}

interface PdfGeneratorProviderProps {
  children: ReactNode;
  columns: PdfColumn[];
  route: string;
  orientation?: PdfOrientation;
  format?: PdfFormat;
}

const PdfGeneratorContext = createContext<PdfGeneratorContextProps | undefined>(undefined);

export function PdfGeneratorProvider({
  children,
  columns,
  route: routeName,
  orientation: initialOrientation = 'Portrait',
  format: initialFormat = 'letter',
}: PdfGeneratorProviderProps) {
  const [unSelectedColumns, setUnSelectedColumns] = useState(columns.filter((col) => !col.selected).map((col) => col.name));
  const [format, setFormat] = useState<PdfFormat>(initialFormat);
  const [orientation, setOrientation] = useState<PdfOrientation>(initialOrientation);
  const [rows, setRows] = useState<string[]>([]);
  const [isLoading, setIsLoading] = useState(false);
  const [routeSrc, setRouteSrc] = useState(route(routeName));

  const rawIframeSrc = useMemo(() => {
    return route(routeName, {
      format,
      orientation,
      rows,
      unSelectedColumns,
    });
  }, [format, orientation, rows, unSelectedColumns, routeName]);

  useEffect(() => {
    setIsLoading(true);
    const timeout = setTimeout(() => {
      setRouteSrc(rawIframeSrc);
    }, 500);

    return () => clearTimeout(timeout);
  }, [rawIframeSrc]);

  const toggleColumnSelection = (columnName: string, isSelected: boolean) => {
    setUnSelectedColumns((prev) => {
      if (isSelected) {
        return prev.filter((col) => col !== columnName);
      }
      return [...prev, columnName];
    });
  };

  const isColumnSelected = (columnName: string) => {
    return !unSelectedColumns.includes(columnName);
  };

  const value = {
    columns,
    routeName,
    format,
    setFormat,
    orientation,
    setOrientation,
    rows,
    setRows,
    toggleColumnSelection,
    isColumnSelected,
    isLoading,
    routeSrc,
    formatOptions,
    orientationOptions,
    setIsLoading,
  };

  return <PdfGeneratorContext value={value}>{children}</PdfGeneratorContext>;
}

export function usePdfGenerator() {
  const context = useContext(PdfGeneratorContext);
  if (context === undefined) {
    throw new Error('usePdfGeneratorContext must be used within a PdfGeneratorProvider');
  }
  return context;
}
