import MemberPdfController from '@/actions/App/Http/Controllers/Pdf/MemberPdfController';
import MissionaryPdfController from '@/actions/App/Http/Controllers/Pdf/MissionaryPdfController';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import { Link } from '@inertiajs/react';

export default function Index() {
  const { t } = useTranslations();
  const options = [
    { label: t('Members report'), url: MemberPdfController.index().url },
    { label: t('Missionaries report'), url: MissionaryPdfController.index().url },
    //   { label: 'Offerings report', url: route('reports.offerings') },
  ];

  return (
    <AppLayout title={t('Reports')} breadcrumbs={[{ title: t('Reports') }]}>
      <Card className="mx-auto mt-10 w-full max-w-2xl">
        <CardContent className="p-4">
          <h2 className="mb-4 text-xl font-semibold">{t('Select an option to generate a PDF')}</h2>
          <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
            {options.map((option) => (
              <Button key={option.url} asChild>
                <Link href={option.url}>{option.label}</Link>
              </Button>
            ))}
          </div>
        </CardContent>
      </Card>
    </AppLayout>
  );
}
