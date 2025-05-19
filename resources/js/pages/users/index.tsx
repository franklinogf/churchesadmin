import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { UserPermission } from '@/enums/user';
import { useTranslations } from '@/hooks/use-translations';
import { useUser } from '@/hooks/use-user';
import AppLayout from '@/layouts/app-layout';
import { type User } from '@/types/models/user';
import { Link } from '@inertiajs/react';
import { columns } from './includes/columns';

interface IndexPageProps {
  users: User[];
}

export default function Index({ users }: IndexPageProps) {
  const { t } = useTranslations<string>();
  const { can: userCan } = useUser();
  return (
    <AppLayout title={t('Users')} breadcrumbs={[{ title: t('Users') }]}>
      <PageTitle>{t('Users')}</PageTitle>
      <DataTable
        headerButton={
          userCan(UserPermission.USERS_CREATE) && (
            <Button asChild>
              <Link href={route('users.create')}>{t('Add :model', { model: t('User') })}</Link>
            </Button>
          )
        }
        data={users}
        columns={columns}
      />
    </AppLayout>
  );
}
