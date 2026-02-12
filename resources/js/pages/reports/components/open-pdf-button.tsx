import { Button } from '@/components/ui/button';
import { useTranslations } from '@/hooks/use-translations';

export const OpenPdfButton = ({ route }: { route: string }) => {
  const { t } = useTranslations();
  return (
    <div className="mb-1 flex justify-end">
      <Button size="sm" onClick={() => window.open(route)}>
        {t('Open in new tab')}
      </Button>
    </div>
  );
};
