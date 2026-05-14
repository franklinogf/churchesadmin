import { usePdfGenerator } from '@/contexts/pdf-generator-context';
import { Loader2Icon } from 'lucide-react';
import { useTranslation } from 'react-i18next';

export function PdfPreview({ route }: { route: string }) {
  const { t: tPages } = useTranslation('pages');
  const { isLoading, setIsLoading } = usePdfGenerator();

  return (
    <div className="relative h-full w-full rounded-lg border">
      {isLoading && (
        <div className="absolute inset-0 z-10 flex flex-col items-center justify-center rounded-lg bg-white/90">
          <Loader2Icon className="animate-spin text-gray-500" size={24} />
          <span className="text-gray-600">{tPages(($) => $.reports.components.pdfPreview.loadingPreview)}</span>
        </div>
      )}
      <iframe className="h-full w-full rounded-lg border" src={route} onLoad={() => setIsLoading(false)} />
    </div>
  );
}
