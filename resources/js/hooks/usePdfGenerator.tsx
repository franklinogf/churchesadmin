import { SelectField } from '@/components/forms/inputs/SelectField';
import { SwitchField } from '@/components/forms/inputs/SwitchField';
import { Card, CardContent } from '@/components/ui/card';
import type { PdfColumn, SelectOption } from '@/types';
import { Loader2Icon } from 'lucide-react';
import { useEffect, useMemo, useState } from 'react';
import { useTranslations } from './use-translations';

const orientations = ['Portrait', 'Landscape'] as const;
const formats = ['letter', 'legal', 'tabloid', 'a0', 'a1', 'a2', 'a3', 'a4', 'a5', 'a6'] as const;

const formatOptions: SelectOption[] = formats.map((format) => ({
  label: format.charAt(0).toUpperCase() + format.slice(1),
  value: format,
}));

const orientationOptions: SelectOption[] = orientations.map((orientation) => ({
  label: orientation.charAt(0).toUpperCase() + orientation.slice(1),
  value: orientation,
}));

export type PdfOrientation = (typeof orientations)[number];
export type PdfFormat = (typeof formats)[number];

interface PdfGeneratorProps {
  columns: PdfColumn[];
  route: string;
  orientation?: PdfOrientation;
  format?: PdfFormat;
}

export function usePdfGenerator({
  columns,
  route: routeName,
  orientation: initialOrientation = 'Portrait',
  format: initialFormat = 'letter',
}: PdfGeneratorProps) {
  const { t } = useTranslations();
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

  const PdfOptionsSelection = () => {
    return (
      <Card>
        <CardContent>
          <div className="mb-4 space-y-4">
            <div className="flex max-w-sm gap-4">
              <SelectField
                className="grow"
                label={'Format'}
                value={format}
                options={formatOptions}
                onChange={(value) => {
                  setFormat(value as PdfFormat);
                }}
              />
              <SelectField
                className="grow"
                label={'Orientation'}
                value={orientation}
                options={orientationOptions}
                onChange={(value) => {
                  setOrientation(value as PdfOrientation);
                }}
              />
            </div>

            <div>
              <h2 className="text-lg font-semibold">{t('Columns to show')}</h2>
              <div className="grid grid-cols-1 gap-2 overflow-y-auto md:grid-cols-2">
                {columns.map((col) => (
                  <SwitchField
                    label={col.label}
                    key={col.name}
                    value={!unSelectedColumns.includes(col.name)}
                    onChange={(value) => {
                      setUnSelectedColumns((prev) => {
                        if (value) {
                          return prev.filter((c) => c !== col.name);
                        }
                        return [...prev, col.name];
                      });
                    }}
                  />
                ))}
              </div>
            </div>
          </div>
        </CardContent>
      </Card>
    );
  };

  const PdfPreview = () => {
    return (
      <div className="relative h-full w-full rounded-lg border">
        {isLoading && (
          <div className="absolute inset-0 z-10 flex flex-col items-center justify-center rounded-lg bg-white/90">
            <Loader2Icon className="animate-spin text-gray-500" size={24} />
            <span className="text-gray-600">{t('Loading preview')}</span>
          </div>
        )}
        <iframe className="h-full w-full rounded-lg border" src={routeSrc} onLoad={() => setIsLoading(false)} />
      </div>
    );
  };

  return {
    PdfOptionsSelection,
    PdfPreview,
    rows,
    onRowsChange: setRows,
    isLoading,
    routeSrc,
  };
}
