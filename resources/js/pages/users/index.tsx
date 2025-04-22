import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { UserPermission } from '@/enums/user';
import { useUser } from '@/hooks/use-permissions';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { type User } from '@/types/models/user';
import { Link } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { columns } from './includes/columns';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Users', href: route('users.index') }];

interface IndexPageProps {
  users: User[];
}
export default function Index({ users }: IndexPageProps) {
  const { t } = useLaravelReactI18n();
  const { can: userCan } = useUser();
  return (
    <AppLayout title={t('Users')} breadcrumbs={breadcrumbs}>
      <PageTitle>{t('Users')}</PageTitle>
      <DataTable
        headerButton={
          userCan(UserPermission.CREATE_USERS) && (
            <Button asChild>
              <Link href={route('users.create')}>{t('Add User')}</Link>
            </Button>
          )
        }
        data={users}
        columns={columns}
      />
    </AppLayout>
  );
}
