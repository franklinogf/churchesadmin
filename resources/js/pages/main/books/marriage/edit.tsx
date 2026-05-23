import BooksController from '@/actions/App/Http/Controllers/BooksController';
import MarriageCertificateController from '@/actions/App/Http/Controllers/MarriageCertificateController';
import { MarriageCertificateForm } from '@/components/forms/marriage-certificate-form';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import type { MarriageCertificate } from '@/types/models/marriage-certificate';
import { useTranslation } from 'react-i18next';

interface EditPageProps {
  marriageCertificate: MarriageCertificate;
}

export default function Edit({ marriageCertificate }: EditPageProps) {
  const { t: tPages } = useTranslation('pages');

  return (
    <AppLayout
      title={tPages(($) => $.main.books.edit_marriageCertificate)}
      breadcrumbs={[
        { title: tPages(($) => $.main.books.title), href: BooksController().url },
        { title: tPages(($) => $.main.books.marriageCertificate.label), href: MarriageCertificateController.index().url },
        { title: tPages(($) => $.main.books.edit_marriageCertificate) },
      ]}
    >
      <PageTitle>{tPages(($) => $.main.books.marriageCertificate.label)}</PageTitle>
      <div className="mt-2 flex w-full items-center justify-center">
        <MarriageCertificateForm marriageCertificate={marriageCertificate} />
      </div>
    </AppLayout>
  );
}
