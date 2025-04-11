import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { UserPermission } from '@/enums/user';
import { useUser } from '@/hooks/use-permissions';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Member } from '@/types/models/member';
import { Link } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { columns } from './includes/columns';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Members',
    href: route('members.index'),
  },
];
interface IndexProps {
  members: Member[];
}
export default function Index({ members }: IndexProps) {
  const { t } = useLaravelReactI18n();
  const { can: userCan } = useUser();
  return (
    <AppLayout breadcrumbs={breadcrumbs} title={t('Members')}>
      <PageTitle>{t('Members')}</PageTitle>
      <DataTable
        headerButton={
          userCan(UserPermission.CREATE_MEMBERS) && (
            <Button asChild>
              <Link href={route('members.create')}>{t('Add Member')}</Link>
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
