import BaptismCertificateController from '@/actions/App/Http/Controllers/BaptismCertificateController';
import { BaptismCertificateForm } from '@/components/forms/baptism-certificate-form';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import { useTranslation } from 'react-i18next';

export default function Create() {
  const { t: tPages } = useTranslation('pages');

  return (
    <AppLayout
      title={tPages(($) => $.main.books.create_baptismCertificate)}
      breadcrumbs={[
        { title: tPages(($) => $.main.books.create_baptismCertificate), href: BaptismCertificateController.index().url },
        { title: tPages(($) => $.main.books.baptismCertificate.label) },
      ]}
    >
      <PageTitle>{tPages(($) => $.main.books.baptismCertificate.label)}</PageTitle>
      <div className="mt-2 flex w-full items-center justify-center">
        <BaptismCertificateForm />
      </div>
    </AppLayout>
  );
}
