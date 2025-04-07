import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { PageTitle } from '@/components/PageTitle';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { Member } from '@/types/models/member';
import { Link } from '@inertiajs/react';
import { ColumnDef } from '@tanstack/react-table';
import { useLaravelReactI18n } from 'laravel-react-i18n';

export const columns: ColumnDef<Member>[] = [
    {
        header: ({ column }) => <DataTableColumnHeader column={column} title="Name" />,
        enableHiding: false,
        accessorKey: 'name',
    },
    {
        header: ({ column }) => <DataTableColumnHeader column={column} title="Last name" />,
        enableHiding: false,
        accessorKey: 'last_name',
    },
    {
        header: ({ column }) => <DataTableColumnHeader column={column} title="Phone" />,
        accessorKey: 'phone',
        enableSorting: false,
    },
    {
        header: ({ column }) => <DataTableColumnHeader column={column} title="Gender" />,
        accessorKey: 'gender',
        cell: ({ row }) => {
            return <Badge className="w-24">{row.getValue('gender')}</Badge>;
        },
    },
    {
        header: ({ column }) => <DataTableColumnHeader column={column} title="Civil Status" />,
        accessorKey: 'civil_status',
        cell: ({ row }) => {
            return <Badge className="w-24">{row.getValue('civil_status')}</Badge>;
        },
    },
];
interface IndexProps {
    members: Member[];
}
export default function Index({ members }: IndexProps) {
    const { t } = useLaravelReactI18n();
    return (
        <AppLayout title={t('Members')}>
            <PageTitle>{t('Members')}</PageTitle>
            <DataTable
                headerButton={
                    <Button asChild>
                        <Link href={route('members.create')}>{t('Add Member')}</Link>
                    </Button>
                }
                data={members}
                rowId="id"
                columns={columns}
            />
        </AppLayout>
    );
}
