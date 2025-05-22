import { RequiredFieldIcon } from '@/components/forms/RequiredFieldIcon';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { Card, CardContent, CardDescription, CardFooter, CardHeader } from '@/components/ui/card';
import { useTranslations } from '@/hooks/use-translations';
import { Progress } from '../ui/progress';

interface FormProps {
  children: React.ReactNode;
  className?: string;
  isSubmitting?: boolean;
  submitLabel?: string;
  showRequiredHelper?: boolean;
  progress?: number | null;
  onSubmit: () => void;
}

export function Form({ children, className, isSubmitting, showRequiredHelper = true, submitLabel, onSubmit, progress }: FormProps) {
  const { t } = useTranslations();
  submitLabel = submitLabel || t('Save');
  return (
    <form
      className={className}
      onSubmit={(e) => {
        e.preventDefault();
        onSubmit();
      }}
    >
      <Card>
        {showRequiredHelper && (
          <CardHeader>
            <CardDescription className="flex items-center gap-1">
              <span>{t('Required fields')}</span>
              <RequiredFieldIcon />
            </CardDescription>
          </CardHeader>
        )}
        <CardContent className="space-y-4">
          {children}
          {progress && (
            <div className="mt-2">
              <Progress value={progress} className="w-full" />
            </div>
          )}
        </CardContent>
        <CardFooter className="flex justify-end">
          <SubmitButton isSubmitting={isSubmitting}>{submitLabel}</SubmitButton>
        </CardFooter>
      </Card>
    </form>
  );
}
