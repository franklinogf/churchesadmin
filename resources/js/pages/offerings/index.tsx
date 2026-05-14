import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';

import OfferingController from '@/actions/App/Http/Controllers/OfferingController';
import Datatable from '@/components/datatable/datatable';
import { PageTitle } from '@/components/PageTitle';
import { useLocaleDate } from '@/hooks/use-locale-date';
import type { BreadcrumbItem } from '@/types';
import { type Offering, type OfferingGroupedByDate } from '@/types/models/offering';
import { Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { columns } from './includes/columns';
import { groupByDateColumns } from './includes/groupByDateColumns';

interface IndexPageProps {
  offerings: Offering[] | OfferingGroupedByDate[];
  date: string | null;
}

export default function Index({ offerings, date }: IndexPageProps) {
  const { t: tPages } = useTranslation('pages');
  const { formatLocaleDate } = useLocaleDate();

  const breadcrumbs: BreadcrumbItem[] = [{ title: tPages(($) => $.offerings.index.offerings), href: OfferingController.index().url }];

  if (date !== null) {
    breadcrumbs.push({
      title: formatLocaleDate(date, { dateStyle: 'long' }),
    });
  }
  return (
    <AppLayout title={tPages(($) => $.offerings.index.offerings)} breadcrumbs={breadcrumbs}>
      <PageTitle>{tPages(($) => $.offerings.index.offerings)}</PageTitle>
      {date !== null ? (
        <Datatable
          renderLeftTop={
            <Button asChild>
              <Link href={OfferingController.create()}>
                {tPages(($) => $.offerings.index.newModel, { model: tPages(($) => $.offerings.index.offering) })}
              </Link>
            </Button>
          }
          data={offerings as Offering[]}
          columns={columns}
          sortingState={[{ id: 'date', desc: true }]}
          visibilityState={{ confirmed: false }}
        />
      ) : (
        <Datatable
          renderLeftTop={
            <Button asChild>
              <Link href={OfferingController.create()}>
                {tPages(($) => $.offerings.index.newModel, { model: tPages(($) => $.offerings.index.offering) })}
              </Link>
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
