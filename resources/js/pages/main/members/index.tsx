import MemberController from '@/actions/App/Http/Controllers/MemberController';
import Datatable from '@/components/datatable/datatable';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { type Member } from '@/types/models/member';
import { Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { columns } from './includes/columns';

interface IndexProps {
  members: Member[];
}
export default function Index({ members }: IndexProps) {
  const { t: tPages } = useTranslation('pages');
  const { can: userCan } = useUser();
  const breadcrumbs: BreadcrumbItem[] = [
    {
      title: tPages(($) => $.main.members.index.members),
    },
  ];

  return (
    <AppLayout breadcrumbs={breadcrumbs} title={tPages(($) => $.main.members.index.members)}>
      <PageTitle>{tPages(($) => $.main.members.index.members)}</PageTitle>
      <Datatable
        renderLeftTop={
          userCan(TenantPermission.MEMBERS_CREATE) && (
            <Button asChild>
              <Link href={MemberController.create()}>
                {tPages(($) => $.main.members.index.addModel, { model: tPages(($) => $.main.members.index.member) })}
              </Link>
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
