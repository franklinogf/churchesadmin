import MemberPdfController from '@/actions/App/Http/Controllers/Pdf/MemberPdfController';
import MissionaryPdfController from '@/actions/App/Http/Controllers/Pdf/MissionaryPdfController';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

export default function Index() {
  const { t: tPages } = useTranslation('pages');
  const options = [
    { label: tPages(($) => $.reports.index.membersReport), url: MemberPdfController.index().url },
    { label: tPages(($) => $.reports.index.missionariesReport), url: MissionaryPdfController.index().url },
    //   { label: 'Offerings report', url: route('reports.offerings') },
  ];

  return (
    <AppLayout title={tPages(($) => $.reports.index.reports)} breadcrumbs={[{ title: tPages(($) => $.reports.index.reports) }]}>
      <Card className="mx-auto mt-10 w-full max-w-2xl">
        <CardContent className="p-4">
          <h2 className="mb-4 text-xl font-semibold">{tPages(($) => $.reports.index.selectAnOptionToGenerateAPdf)}</h2>
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
