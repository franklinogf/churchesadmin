import BooksController from '@/actions/App/Http/Controllers/BooksController';
import ConfirmationCertificateController from '@/actions/App/Http/Controllers/ConfirmationCertificateController';
import { ConfirmationCertificateForm } from '@/components/forms/confirmation-certificate-form';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import type { ConfirmationCertificate } from '@/types/models/confirmation-certificate';
import { useTranslation } from 'react-i18next';

interface EditPageProps {
  confirmationCertificate: ConfirmationCertificate;
}

export default function Edit({ confirmationCertificate }: EditPageProps) {
  const { t: tPages } = useTranslation('pages');

  return (
    <AppLayout
      title={tPages(($) => $.main.books.edit_confirmationCertificate)}
      breadcrumbs={[
        { title: tPages(($) => $.main.books.title), href: BooksController().url },
        { title: tPages(($) => $.main.books.confirmationCertificate.label), href: ConfirmationCertificateController.index().url },
        { title: tPages(($) => $.main.books.edit_confirmationCertificate) },
      ]}
    >
      <PageTitle>{tPages(($) => $.main.books.confirmationCertificate.label)}</PageTitle>
      <div className="mt-2 flex w-full items-center justify-center">
        <ConfirmationCertificateForm confirmationCertificate={confirmationCertificate} />
      </div>
    </AppLayout>
  );
}
