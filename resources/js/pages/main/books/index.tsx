import BaptismCertificateController from '@/actions/App/Http/Controllers/BaptismCertificateController';
import { Datatable } from '@/components/datatable/datatable';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import AppLayout from '@/layouts/app-layout';
import type { BaptismCertificate } from '@/types/models/baptism-certificate';
import { Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { columns } from './includes/columns';

interface IndexPageProps {
  baptismCertificates: BaptismCertificate[];
}

export default function Index({ baptismCertificates }: IndexPageProps) {
  const { t: tPages } = useTranslation('pages');
  const { can: userCan } = useUser();

  return (
    <AppLayout breadcrumbs={[{ title: tPages(($) => $.main.books.title) }]} title={tPages(($) => $.main.books.title)}>
      <PageTitle>{tPages(($) => $.main.books.title)}</PageTitle>
      <div className="mx-auto w-full max-w-5xl">
        <Datatable
          renderLeftTop={
            userCan(TenantPermission.BOOKS_CREATE) && (
              <Link href={BaptismCertificateController.create().url}>
                <Button size="sm">{tPages(($) => $.main.books.create_baptismCertificate)}</Button>
              </Link>
            )
          }
          columns={columns}
          data={baptismCertificates}
          rowId="id"
        />
      </div>
    </AppLayout>
  );
}
