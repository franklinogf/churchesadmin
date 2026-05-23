import BooksController from '@/actions/App/Http/Controllers/BooksController';
import NegativaController from '@/actions/App/Http/Controllers/NegativaController';
import { NegativaForm } from '@/components/forms/negativa-form';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import type { Negativa } from '@/types/models/negativa';
import { useTranslation } from 'react-i18next';

interface EditPageProps {
  negativa: Negativa;
}

export default function Edit({ negativa }: EditPageProps) {
  const { t: tPages } = useTranslation('pages');

  return (
    <AppLayout
      title={tPages(($) => $.main.books.edit_negativa)}
      breadcrumbs={[
        { title: tPages(($) => $.main.books.title), href: BooksController().url },
        { title: tPages(($) => $.main.books.negativa.label), href: NegativaController.index().url },
        { title: tPages(($) => $.main.books.edit_negativa) },
      ]}
    >
      <PageTitle>{tPages(($) => $.main.books.negativa.label)}</PageTitle>
      <div className="mt-2 flex w-full items-center justify-center">
        <NegativaForm negativa={negativa} />
      </div>
    </AppLayout>
  );
}
