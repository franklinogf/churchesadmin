import MemberController from '@/actions/App/Http/Controllers/MemberController';
import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { TenantPermission } from '@/enums/TenantPermission';
import { useTranslations } from '@/hooks/use-translations';
import { useUser } from '@/hooks/use-user';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { type Member } from '@/types/models/member';
import { Link } from '@inertiajs/react';
import { columns } from './includes/columns';

interface IndexProps {
  members: Member[];
}
export default function Index({ members }: IndexProps) {
  const { t } = useTranslations();
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
          userCan(TenantPermission.MEMBERS_CREATE) && (
            <Button asChild>
              <Link href={MemberController.create()}>{t('Add :model', { model: t('Member') })}</Link>
            </Button>
          )
        }
        data={members}
        rowId="id"
        visibilityState={{ active: false, civilStatus: false }}
        filteringState={[{ id: 'active', value: true }]}
        columns={columns}
      />
    </AppLayout>
  );
}
