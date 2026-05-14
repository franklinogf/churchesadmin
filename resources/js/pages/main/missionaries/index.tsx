import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { TenantPermission } from '@/enums/TenantPermission';
import AppLayout from '@/layouts/app-layout';

import MissionaryController from '@/actions/App/Http/Controllers/MissionaryController';
import Datatable from '@/components/datatable/datatable';
import { useUser } from '@/hooks/use-user';
import { type Missionary } from '@/types/models/missionary';
import { Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { columns } from './includes/columns';

interface IndexPageProps {
  missionaries: Missionary[];
}

export default function Index({ missionaries }: IndexPageProps) {
  const { t: tPages } = useTranslation('pages');
  const { can: userCan } = useUser();

  return (
    <AppLayout
      title={tPages(($) => $.main.missionaries.index.missionaries)}
      breadcrumbs={[{ title: tPages(($) => $.main.missionaries.index.missionaries) }]}
    >
      <PageTitle>{tPages(($) => $.main.missionaries.index.missionaries)}</PageTitle>
      <Datatable
        renderLeftTop={
          userCan(TenantPermission.MISSIONARIES_CREATE) && (
            <Button asChild>
              <Link href={MissionaryController.create()}>
                {tPages(($) => $.main.missionaries.index.addModel, { model: tPages(($) => $.main.missionaries.index.missionary) })}
              </Link>
            </Button>
          )
        }
        data={missionaries}
        columns={columns}
      />
    </AppLayout>
  );
}
