import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Wallet } from '@/types/models/wallet';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { columns } from './includes/columns';

interface IndexPageProps {
  wallets: Wallet[];
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Wallets',
    href: route('wallets.index'),
  },
];

export default function Index({ wallets }: IndexPageProps) {
  const { t } = useLaravelReactI18n();
  return (
    <AppLayout breadcrumbs={breadcrumbs} title={t('Wallets')}>
      <PageTitle>{t('Wallets')}</PageTitle>
      <DataTable data={wallets} columns={columns} />
    </AppLayout>
  );
}
