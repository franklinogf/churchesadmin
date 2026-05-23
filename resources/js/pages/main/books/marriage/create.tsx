import BooksController from '@/actions/App/Http/Controllers/BooksController';
import MarriageCertificateController from '@/actions/App/Http/Controllers/MarriageCertificateController';
import { MarriageCertificateForm } from '@/components/forms/marriage-certificate-form';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import { useTranslation } from 'react-i18next';

export default function Create() {
  const { t: tPages } = useTranslation('pages');

  return (
    <AppLayout
      title={tPages(($) => $.main.books.create_marriageCertificate)}
      breadcrumbs={[
        { title: tPages(($) => $.main.books.title), href: BooksController().url },
        { title: tPages(($) => $.main.books.marriageCertificate.label), href: MarriageCertificateController.index().url },
        { title: tPages(($) => $.main.books.create_marriageCertificate) },
      ]}
    >
      <PageTitle>{tPages(($) => $.main.books.marriageCertificate.label)}</PageTitle>
      <div className="mt-2 flex w-full items-center justify-center">
        <MarriageCertificateForm />
      </div>
    </AppLayout>
  );
}
