import BooksController from '@/actions/App/Http/Controllers/BooksController';
import NegativaController from '@/actions/App/Http/Controllers/NegativaController';
import { NegativaForm } from '@/components/forms/negativa-form';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import { useTranslation } from 'react-i18next';

export default function Create() {
  const { t: tPages } = useTranslation('pages');

  return (
    <AppLayout
      title={tPages(($) => $.main.books.create_negativa)}
      breadcrumbs={[
        { title: tPages(($) => $.main.books.title), href: BooksController().url },
        { title: tPages(($) => $.main.books.negativa.label), href: NegativaController.index().url },
        { title: tPages(($) => $.main.books.create_negativa) },
      ]}
    >
      <PageTitle>{tPages(($) => $.main.books.negativa.label)}</PageTitle>
      <div className="mt-2 flex w-full items-center justify-center">
        <NegativaForm />
      </div>
    </AppLayout>
  );
}
