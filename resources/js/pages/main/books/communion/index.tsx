import BooksController from '@/actions/App/Http/Controllers/BooksController';
import FirstCommunionCertificateController from '@/actions/App/Http/Controllers/FirstCommunionCertificateController';
import { Datatable } from '@/components/datatable/datatable';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import AppLayout from '@/layouts/app-layout';
import type { FirstCommunionCertificate } from '@/types/models/first-communion-certificate';
import { Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { columns } from './includes/columns';

interface IndexPageProps {
  firstCommunionCertificates: FirstCommunionCertificate[];
}

export default function Index({ firstCommunionCertificates }: IndexPageProps) {
  const { t: tPages } = useTranslation('pages');
  const { can: userCan } = useUser();

  return (
    <AppLayout
      breadcrumbs={[
        { title: tPages(($) => $.main.books.title), href: BooksController().url },
        { title: tPages(($) => $.main.books.communionCertificate.label) },
      ]}
      title={tPages(($) => $.main.books.communionCertificate.label)}
    >
      <PageTitle>{tPages(($) => $.main.books.communionCertificate.label)}</PageTitle>
      <div className="mx-auto w-full max-w-5xl">
        <Datatable
          renderLeftTop={
            userCan(TenantPermission.BOOKS_CREATE) && (
              <Link href={FirstCommunionCertificateController.create().url}>
                <Button size="sm">{tPages(($) => $.main.books.create_communionCertificate)}</Button>
              </Link>
            )
          }
          columns={columns}
          data={firstCommunionCertificates}
          rowId="id"
        />
      </div>
    </AppLayout>
  );
}
