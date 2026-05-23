import BooksController from '@/actions/App/Http/Controllers/BooksController';
import ConfirmationCertificateController from '@/actions/App/Http/Controllers/ConfirmationCertificateController';
import { ConfirmationCertificateForm } from '@/components/forms/confirmation-certificate-form';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import { useTranslation } from 'react-i18next';

export default function Create() {
  const { t: tPages } = useTranslation('pages');

  return (
    <AppLayout
      title={tPages(($) => $.main.books.create_confirmationCertificate)}
      breadcrumbs={[
        { title: tPages(($) => $.main.books.title), href: BooksController().url },
        { title: tPages(($) => $.main.books.confirmationCertificate.label), href: ConfirmationCertificateController.index().url },
        { title: tPages(($) => $.main.books.create_confirmationCertificate) },
      ]}
    >
      <PageTitle>{tPages(($) => $.main.books.confirmationCertificate.label)}</PageTitle>
      <div className="mt-2 flex w-full items-center justify-center">
        <ConfirmationCertificateForm />
      </div>
    </AppLayout>
  );
}
