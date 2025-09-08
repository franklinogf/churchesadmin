import { Button } from '@/components/ui/button';
import { usePdfGenerator } from '@/contexts/pdf-generator-context';
import { useTranslations } from '@/hooks/use-translations';

export const OpenPdfButton = () => {
  const { t } = useTranslations();
  const { routeSrc } = usePdfGenerator();
  return (
    <div className="mb-1 flex justify-end">
      <Button size="sm" onClick={() => window.open(routeSrc)}>
        {t('Open in new tab')}
      </Button>
    </div>
  );
};
