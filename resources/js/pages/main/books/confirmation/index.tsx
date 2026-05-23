import BooksController from '@/actions/App/Http/Controllers/BooksController';
import ConfirmationCertificateController from '@/actions/App/Http/Controllers/ConfirmationCertificateController';
import { Datatable } from '@/components/datatable/datatable';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import AppLayout from '@/layouts/app-layout';
import type { ConfirmationCertificate } from '@/types/models/confirmation-certificate';
import { Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { columns } from './includes/columns';

interface IndexPageProps {
  confirmationCertificates: ConfirmationCertificate[];
}

export default function Index({ confirmationCertificates }: IndexPageProps) {
  const { t: tPages } = useTranslation('pages');
  const { can: userCan } = useUser();

  return (
    <AppLayout
      breadcrumbs={[
        { title: tPages(($) => $.main.books.title), href: BooksController().url },
        { title: tPages(($) => $.main.books.confirmationCertificate.label) },
      ]}
      title={tPages(($) => $.main.books.confirmationCertificate.label)}
    >
      <PageTitle>{tPages(($) => $.main.books.confirmationCertificate.label)}</PageTitle>
      <div className="mx-auto w-full max-w-5xl">
        <Datatable
          renderLeftTop={
            userCan(TenantPermission.BOOKS_CREATE) && (
              <Link href={ConfirmationCertificateController.create().url}>
                <Button size="sm">{tPages(($) => $.main.books.create_confirmationCertificate)}</Button>
              </Link>
            )
          }
          columns={columns}
          data={confirmationCertificates}
          rowId="id"
        />
      </div>
    </AppLayout>
  );
}
