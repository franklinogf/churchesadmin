import { usePdfGenerator } from '@/contexts/pdf-generator-context';
import { useTranslations } from '@/hooks/use-translations';
import { Loader2Icon } from 'lucide-react';

export function PdfPreview() {
  const { t } = useTranslations();
  const { isLoading, routeSrc, setIsLoading } = usePdfGenerator();

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
}
