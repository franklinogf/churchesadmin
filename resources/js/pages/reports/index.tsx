import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { Link } from '@inertiajs/react';
import { useMemo } from 'react';

export default function Index() {
  const options = useMemo(
    () => [
      { label: 'Members report', url: route('reports.members') },
      { label: 'Missionaries report', url: route('reports.missionaries') },
      //   { label: 'Offerings report', url: route('reports.offerings') },
    ],
    [],
  );
  return (
    <AppLayout title="Reports" breadcrumbs={[{ title: 'Reports' }]}>
      <Card className="mx-auto mt-10 w-full max-w-2xl">
        <CardContent className="p-4">
          <h2 className="mb-4 text-xl font-semibold">Select an option to generate a PDF</h2>
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
