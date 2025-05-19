import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { AlertCircleIcon, MinusIcon } from 'lucide-react';

export function FormErrorList({ errors }: { errors?: Record<string, string> }) {
  const { t } = useLaravelReactI18n();
  if (!errors || Object.keys(errors).length === 0) {
    return null;
  }
  return (
    <Alert variant="destructive" className="mb-4">
      <AlertCircleIcon className="size-4" />
      <AlertTitle>{t('Error submiting the form')}</AlertTitle>
      {Object.entries(errors).map(([key, value]) => (
        <AlertDescription key={key} className="flex items-center gap-2 text-sm">
          <MinusIcon className="size-4" /> {value}
        </AlertDescription>
      ))}
    </Alert>
  );
}
