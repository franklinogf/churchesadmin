import BooksController from '@/actions/App/Http/Controllers/BooksController';
import NegativaController from '@/actions/App/Http/Controllers/NegativaController';
import { Datatable } from '@/components/datatable/datatable';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import AppLayout from '@/layouts/app-layout';
import type { Negativa } from '@/types/models/negativa';
import { Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { columns } from './includes/columns';

interface IndexPageProps {
  negativas: Negativa[];
}

export default function Index({ negativas }: IndexPageProps) {
  const { t: tPages } = useTranslation('pages');
  const { can: userCan } = useUser();

  return (
    <AppLayout
      breadcrumbs={[
        { title: tPages(($) => $.main.books.title), href: BooksController().url },
        { title: tPages(($) => $.main.books.negativa.label) },
      ]}
      title={tPages(($) => $.main.books.negativa.label)}
    >
      <PageTitle>{tPages(($) => $.main.books.negativa.label)}</PageTitle>
      <div className="mx-auto w-full max-w-5xl">
        <Datatable
          renderLeftTop={
            userCan(TenantPermission.BOOKS_CREATE) && (
              <Link href={NegativaController.create().url}>
                <Button size="sm">{tPages(($) => $.main.books.create_negativa)}</Button>
              </Link>
            )
          }
          columns={columns}
          data={negativas}
          rowId="id"
        />
      </div>
    </AppLayout>
  );
}
