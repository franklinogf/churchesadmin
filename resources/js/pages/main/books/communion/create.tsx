import BooksController from '@/actions/App/Http/Controllers/BooksController';
import FirstCommunionCertificateController from '@/actions/App/Http/Controllers/FirstCommunionCertificateController';
import { FirstCommunionCertificateForm } from '@/components/forms/first-communion-certificate-form';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import { useTranslation } from 'react-i18next';

export default function Create() {
  const { t: tPages } = useTranslation('pages');

  return (
    <AppLayout
      title={tPages(($) => $.main.books.create_communionCertificate)}
      breadcrumbs={[
        { title: tPages(($) => $.main.books.title), href: BooksController().url },
        { title: tPages(($) => $.main.books.communionCertificate.label), href: FirstCommunionCertificateController.index().url },
        { title: tPages(($) => $.main.books.create_communionCertificate) },
      ]}
    >
      <PageTitle>{tPages(($) => $.main.books.communionCertificate.label)}</PageTitle>
      <div className="mt-2 flex w-full items-center justify-center">
        <FirstCommunionCertificateForm />
      </div>
    </AppLayout>
  );
}
