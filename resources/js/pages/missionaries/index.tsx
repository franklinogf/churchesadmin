import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import AppLayout from '@/layouts/app-layout';
import { Missionary } from '@/types/models/missionary';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { columns } from './includes/columns';

const breadcrumbs = [
    {
        title: 'Missionaries',
        href: route('missionaries.index'),
    },
];

interface IndexPageProps {
    missionaries: Missionary[];
}

export default function Index({ missionaries }: IndexPageProps) {
    const { t } = useLaravelReactI18n();
    return (
        <AppLayout title={t('Missionaries')} breadcrumbs={breadcrumbs}>
            <PageTitle>{t('Missionaries')}</PageTitle>
            <DataTable data={missionaries} columns={columns} />
        </AppLayout>
    );
}
