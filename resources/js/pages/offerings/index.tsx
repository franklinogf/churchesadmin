import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';

import { PageTitle } from '@/components/PageTitle';
import { useLocaleDate } from '@/hooks/use-locale-date';
import type { BreadcrumbItem } from '@/types';
import { type Offering, type OfferingGroupedByDate } from '@/types/models/offering';
import { Link } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { columns } from './includes/columns';
import { groupByDateColumns } from './includes/groupByDateColumns';

interface IndexPageProps {
  offerings: Offering[] | OfferingGroupedByDate[];
  date: string | null;
}

export default function Index({ offerings, date }: IndexPageProps) {
  const { t } = useLaravelReactI18n();
  const { formatLocaleDate } = useLocaleDate();

  const breadcrumbs: BreadcrumbItem[] = [{ title: t('Offerings'), href: route('offerings.index') }];

  if (date !== null) {
    breadcrumbs.push({
      title: formatLocaleDate(date, { dateStyle: 'long' }),
    });
  }
  return (
    <AppLayout title={t('Offerings')} breadcrumbs={breadcrumbs}>
      <PageTitle>{t('Offerings')}</PageTitle>
      {date !== null ? (
        <DataTable
          headerButton={
            <Button asChild>
              <Link href={route('offerings.create')}>{t('New :model', { model: t('Offering') })}</Link>
            </Button>
          }
          data={offerings as Offering[]}
          columns={columns}
          sortingState={[{ id: 'date', desc: true }]}
          visibilityState={{ confirmed: false }}
        />
      ) : (
        <DataTable
          headerButton={
            <Button asChild>
              <Link href={route('offerings.create')}>{t('New :model', { model: t('Offering') })}</Link>
            </Button>
          }
          data={offerings as OfferingGroupedByDate[]}
          columns={groupByDateColumns}
          sortingState={[{ id: 'date', desc: true }]}
        />
      )}
    </AppLayout>
  );
}
