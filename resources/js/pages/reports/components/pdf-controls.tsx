import { SelectField } from '@/components/forms/inputs/SelectField';
import { SwitchField } from '@/components/forms/inputs/SwitchField';
import { Card, CardContent } from '@/components/ui/card';
import { usePdfGenerator, type PdfFormat, type PdfOrientation } from '@/contexts/pdf-generator-context';
import { useTranslations } from '@/hooks/use-translations';

export function PdfControls() {
  const { t } = useTranslations();
  const { columns, format, setFormat, orientation, setOrientation, isColumnSelected, toggleColumnSelection, formatOptions, orientationOptions } =
    usePdfGenerator();

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
              onValueChange={(value) => {
                setFormat(value as PdfFormat);
              }}
            />
            <SelectField
              className="grow"
              label={'Orientation'}
              value={orientation}
              options={orientationOptions}
              onValueChange={(value) => {
                setOrientation(value as PdfOrientation);
              }}
            />
          </div>

          <div>
            <h2 className="text-lg font-semibold">{t('Columns to show')}</h2>
            <div className="grid grid-cols-1 gap-2 overflow-y-auto md:grid-cols-2">
              {columns.map((col) => (
                <SwitchField
                  className="max-w-fit"
                  label={col.label}
                  key={col.name}
                  checked={isColumnSelected(col.name)}
                  onCheckedChange={(value) => {
                    toggleColumnSelection(col.name, value);
                  }}
                />
              ))}
            </div>
          </div>
        </div>
      </CardContent>
    </Card>
  );
}
