import { Button } from '@/components/ui/button';
import { useTranslation } from 'react-i18next';

export const OpenPdfButton = ({ route }: { route: string }) => {
  const { t: tPages } = useTranslation('pages');
  return (
    <div className="mb-1 flex justify-end">
      <Button size="sm" onClick={() => window.open(route)}>
        {tPages(($) => $.reports.components.openPdfButton.openInNewTab)}
      </Button>
    </div>
  );
};
