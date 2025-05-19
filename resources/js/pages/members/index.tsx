import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { UserPermission } from '@/enums/user';
import { useUser } from '@/hooks/use-user';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { type Member } from '@/types/models/member';
import { Link } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { columns } from './includes/columns';

interface IndexProps {
  members: Member[];
}
export default function Index({ members }: IndexProps) {
  const { t } = useLaravelReactI18n();
  const { can: userCan } = useUser();
  const breadcrumbs: BreadcrumbItem[] = [
    {
      title: t('Members'),
    },
  ];
  return (
    <AppLayout breadcrumbs={breadcrumbs} title={t('Members')}>
      <PageTitle>{t('Members')}</PageTitle>
      <DataTable
        headerButton={
          userCan(UserPermission.MEMBERS_CREATE) && (
            <Button asChild>
              <Link href={route('members.create')}>{t('Add :model', { model: t('Member') })}</Link>
            </Button>
          )
        }
        data={members}
        rowId="id"
        columns={columns}
      />
    </AppLayout>
  );
}
