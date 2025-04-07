import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import AppLayout from '@/layouts/app-layout';
import useConfirmationStore from '@/stores/confirmationStore';
import { Tag } from '@/types/models/tags';
import { Link, router } from '@inertiajs/react';
import { ColumnDef } from '@tanstack/react-table';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { Edit2Icon, MoreHorizontalIcon, Trash2Icon } from 'lucide-react';

export const columns: ColumnDef<Tag>[] = [
    {
        enableHiding: false,
        header: 'Name',
        accessorKey: 'name',
    },
    {
        id: 'actions',
        enableHiding: false,
        enableSorting: false,
        size: 0,
        cell: ({ row }) => {
            const { t } = useLaravelReactI18n();
            const { openConfirmation } = useConfirmationStore();
            return (
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="ghost" size="sm">
                            <span className="sr-only">{t('Actions')}</span>
                            <MoreHorizontalIcon />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent>
                        <DropdownMenuItem asChild>
                            <Link href={route('members.edit', row.original.id)}>
                                <Edit2Icon className="size-3" />
                                <span>{t('Edit')}</span>
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            variant="destructive"
                            onClick={() => {
                                openConfirmation({
                                    title: t('Are you sure you want to delete this member?'),
                                    description: t('You can restore it any time.'),
                                    actionLabel: t('Delete'),
                                    cancelLabel: t('Cancel'),
                                    onAction: () => {
                                        router.delete(route('members.destroy', row.original.id), {
                                            preserveState: true,
                                            preserveScroll: true,
                                        });
                                    },
                                });
                            }}
                        >
                            <Trash2Icon className="size-3" />
                            <span>{t('Delete')}</span>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            );
        },
    },
];

interface IndexPageProps {
    skills: Tag[];
}
export default function Index({ skills }: IndexPageProps) {
    const { t } = useLaravelReactI18n();
    return (
        <AppLayout title={t('Skills')}>
            <PageTitle>{t('Skills')}</PageTitle>
            <DataTable columns={columns} data={skills} rowId="id" />
        </AppLayout>
    );
}
