import BooksController from '@/actions/App/Http/Controllers/BooksController';
import MarriageCertificateController from '@/actions/App/Http/Controllers/MarriageCertificateController';
import { Datatable } from '@/components/datatable/datatable';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import AppLayout from '@/layouts/app-layout';
import type { MarriageCertificate } from '@/types/models/marriage-certificate';
import { Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { columns } from './includes/columns';

interface IndexPageProps {
  marriageCertificates: MarriageCertificate[];
}

export default function Index({ marriageCertificates }: IndexPageProps) {
  const { t: tPages } = useTranslation('pages');
  const { can: userCan } = useUser();

  return (
    <AppLayout
      breadcrumbs={[
        { title: tPages(($) => $.main.books.title), href: BooksController().url },
        { title: tPages(($) => $.main.books.marriageCertificate.label) },
      ]}
      title={tPages(($) => $.main.books.marriageCertificate.label)}
    >
      <PageTitle>{tPages(($) => $.main.books.marriageCertificate.label)}</PageTitle>
      <div className="mx-auto w-full max-w-5xl">
        <Datatable
          renderLeftTop={
            userCan(TenantPermission.BOOKS_CREATE) && (
              <Link href={MarriageCertificateController.create().url}>
                <Button size="sm">{tPages(($) => $.main.books.create_marriageCertificate)}</Button>
              </Link>
            )
          }
          columns={columns}
          data={marriageCertificates}
          rowId="id"
        />
      </div>
    </AppLayout>
  );
}
