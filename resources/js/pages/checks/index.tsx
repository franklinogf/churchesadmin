import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import type { Check } from '@/types/models/check';
import { Link } from '@inertiajs/react';
import { columns } from './includes/columns';

interface IndexPageProps {
  checks: Check[];
}
export default function Index({ checks }: IndexPageProps) {
  return (
    <AppLayout title="Checks" breadcrumbs={[{ title: 'Checks', href: route('checks.index') }]}>
      <PageTitle>Checks</PageTitle>
      <div className="mx-auto mt-4 w-full max-w-2xl">
        <DataTable
          headerButton={
            <Button size="sm">
              <Link href={route('checks.create')}>New Check</Link>
            </Button>
          }
          data={checks}
          columns={columns}
        />
      </div>
    </AppLayout>
  );
}
