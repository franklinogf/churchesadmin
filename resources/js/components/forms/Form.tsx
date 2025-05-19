import { RequiredFieldIcon } from '@/components/forms/RequiredFieldIcon';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { Card, CardContent, CardDescription, CardFooter, CardHeader } from '@/components/ui/card';
import { useTranslations } from '@/hooks/use-translations';

interface FormProps {
  children: React.ReactNode;
  className?: string;
  isSubmitting?: boolean;
  submitLabel?: string;
  showRequiredHelper?: boolean;
  onSubmit: () => void;
}

export function Form({ children, className, isSubmitting, showRequiredHelper = true, submitLabel, onSubmit }: FormProps) {
  const { t } = useTranslations<string>();
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
        <CardContent className="space-y-4">{children}</CardContent>
        <CardFooter className="flex justify-end">
          <SubmitButton isSubmitting={isSubmitting}>{submitLabel}</SubmitButton>
        </CardFooter>
      </Card>
    </form>
  );
}
