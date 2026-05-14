import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { AlertCircleIcon, MinusIcon } from 'lucide-react';
import { useTranslation } from 'react-i18next';

export function FormErrorList({ errors }: { errors?: Record<string, string> }) {
  const { t: tCommon } = useTranslation('common');
  if (!errors || Object.keys(errors).length === 0) {
    return null;
  }
  return (
    <Alert variant="destructive" className="mb-4">
      <AlertCircleIcon className="size-4" />
      <AlertTitle>{tCommon(($) => $.components.forms.formErrorList.errorSubmitingTheForm)}</AlertTitle>
      {Object.entries(errors).map(([key, value]) => (
        <AlertDescription key={key} className="flex items-center gap-2 text-sm">
          <MinusIcon className="size-4" /> {value}
        </AlertDescription>
      ))}
    </Alert>
  );
}
