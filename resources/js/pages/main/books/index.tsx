import BaptismCertificateController from '@/actions/App/Http/Controllers/BaptismCertificateController';
import ConfirmationCertificateController from '@/actions/App/Http/Controllers/ConfirmationCertificateController';
import FirstCommunionCertificateController from '@/actions/App/Http/Controllers/FirstCommunionCertificateController';
import MarriageCertificateController from '@/actions/App/Http/Controllers/MarriageCertificateController';
import NegativaController from '@/actions/App/Http/Controllers/NegativaController';
import { PageTitle } from '@/components/PageTitle';
import { Card, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { Link } from '@inertiajs/react';
import { BookOpenIcon, ChevronRightIcon } from 'lucide-react';
import { useTranslation } from 'react-i18next';

interface IndexPageProps {
  baptismCount: number;
  confirmationCount: number;
  marriageCount: number;
  communionCount: number;
  negativaCount: number;
}

export default function Index({ baptismCount, confirmationCount, marriageCount, communionCount, negativaCount }: IndexPageProps) {
  const { t: tPages } = useTranslation('pages');

  const books = [
    {
      label: tPages(($) => $.main.books.baptismCertificate.label),
      description: tPages(($) => $.main.books.baptismCertificate.description),
      count: baptismCount,
      href: BaptismCertificateController.index().url,
    },
    {
      label: tPages(($) => $.main.books.confirmationCertificate.label),
      description: tPages(($) => $.main.books.confirmationCertificate.description),
      count: confirmationCount,
      href: ConfirmationCertificateController.index().url,
    },
    {
      label: tPages(($) => $.main.books.marriageCertificate.label),
      description: tPages(($) => $.main.books.marriageCertificate.description),
      count: marriageCount,
      href: MarriageCertificateController.index().url,
    },
    {
      label: tPages(($) => $.main.books.communionCertificate.label),
      description: tPages(($) => $.main.books.communionCertificate.description),
      count: communionCount,
      href: FirstCommunionCertificateController.index().url,
    },
    {
      label: tPages(($) => $.main.books.negativa.label),
      description: tPages(($) => $.main.books.negativa.description),
      count: negativaCount,
      href: NegativaController.index().url,
    },
  ];

  return (
    <AppLayout breadcrumbs={[{ title: tPages(($) => $.main.books.title) }]} title={tPages(($) => $.main.books.title)}>
      <PageTitle>{tPages(($) => $.main.books.title)}</PageTitle>
      <div className="mx-auto w-full max-w-2xl">
        <div className="flex flex-col gap-4">
          {books.map((book) => (
            <Link key={book.href} href={book.href} className="group block">
              <Card className="hover:bg-accent/50 transition-colors">
                <CardHeader className="flex flex-row items-center gap-4 pb-2">
                  <BookOpenIcon className="text-muted-foreground size-6" />
                  <div className="flex-1">
                    <CardTitle className="text-base">{book.label}</CardTitle>
                    <CardDescription>{book.description}</CardDescription>
                  </div>
                  <div className="flex items-center gap-3">
                    <span className="text-muted-foreground text-sm">{book.count}</span>
                    <ChevronRightIcon className="text-muted-foreground size-4 transition-transform group-hover:translate-x-0.5" />
                  </div>
                </CardHeader>
              </Card>
            </Link>
          ))}
        </div>
      </div>
    </AppLayout>
  );
}
