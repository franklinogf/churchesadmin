import UserController from '@/actions/App/Http/Controllers/UserController';
import Datatable from '@/components/datatable/datatable';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import AppLayout from '@/layouts/app-layout';
import { type User } from '@/types/models/user';
import { Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { columns } from './includes/columns';

interface IndexPageProps {
  users: User[];
}

export default function Index({ users }: IndexPageProps) {
  const { t: tPages } = useTranslation('pages');
  const { can: userCan } = useUser();
  return (
    <AppLayout title={tPages(($) => $.main.users.index.users)} breadcrumbs={[{ title: tPages(($) => $.main.users.index.users) }]}>
      <PageTitle>{tPages(($) => $.main.users.index.users)}</PageTitle>
      <Datatable
        renderLeftTop={
          userCan(TenantPermission.USERS_CREATE) && (
            <Button asChild>
              <Link href={UserController.create()}>
                {tPages(($) => $.main.users.index.addModel, { model: tPages(($) => $.main.users.index.user) })}
              </Link>
            </Button>
          )
        }
        data={users}
        columns={columns}
      />
    </AppLayout>
  );
}
