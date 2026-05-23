import BaptismCertificateController from '@/actions/App/Http/Controllers/BaptismCertificateController';
import BooksController from '@/actions/App/Http/Controllers/BooksController';
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
    <AppLayout
      breadcrumbs={[
        { title: tPages(($) => $.main.books.title), href: BooksController().url },
        { title: tPages(($) => $.main.books.baptismCertificate.label) },
      ]}
      title={tPages(($) => $.main.books.baptismCertificate.label)}
    >
      <PageTitle>{tPages(($) => $.main.books.baptismCertificate.label)}</PageTitle>
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
