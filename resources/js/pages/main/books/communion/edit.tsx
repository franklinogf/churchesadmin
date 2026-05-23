import BooksController from '@/actions/App/Http/Controllers/BooksController';
import FirstCommunionCertificateController from '@/actions/App/Http/Controllers/FirstCommunionCertificateController';
import { FirstCommunionCertificateForm } from '@/components/forms/first-communion-certificate-form';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import type { FirstCommunionCertificate } from '@/types/models/first-communion-certificate';
import { useTranslation } from 'react-i18next';

interface EditPageProps {
  firstCommunionCertificate: FirstCommunionCertificate;
}

export default function Edit({ firstCommunionCertificate }: EditPageProps) {
  const { t: tPages } = useTranslation('pages');

  return (
    <AppLayout
      title={tPages(($) => $.main.books.edit_communionCertificate)}
      breadcrumbs={[
        { title: tPages(($) => $.main.books.title), href: BooksController().url },
        { title: tPages(($) => $.main.books.communionCertificate.label), href: FirstCommunionCertificateController.index().url },
        { title: tPages(($) => $.main.books.edit_communionCertificate) },
      ]}
    >
      <PageTitle>{tPages(($) => $.main.books.communionCertificate.label)}</PageTitle>
      <div className="mt-2 flex w-full items-center justify-center">
        <FirstCommunionCertificateForm firstCommunionCertificate={firstCommunionCertificate} />
      </div>
    </AppLayout>
  );
}
