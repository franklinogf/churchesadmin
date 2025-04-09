import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { SwitchField } from '@/components/forms/inputs/SwitchField';
import TranslatableInput from '@/components/forms/inputs/TranslatableInputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader } from '@/components/ui/dialog';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { UserPermission } from '@/enums/user';
import AppLayout from '@/layouts/app-layout';
import { emptyTranslations, userCan } from '@/lib/utils';
import useConfirmationStore from '@/stores/confirmationStore';
import type { BreadcrumbItem } from '@/types';
import { Tag } from '@/types/models/tag';
import { router, useForm } from '@inertiajs/react';
import { DialogTitle, DialogTrigger } from '@radix-ui/react-dialog';
import { ColumnDef } from '@tanstack/react-table';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { Edit2Icon, MoreHorizontalIcon, Trash2Icon } from 'lucide-react';
import { useState } from 'react';

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
            const category = row.original;
            if (category.isRegular && !userCan(UserPermission.UPDATE_REGULAR_TAG) && !userCan(UserPermission.DELETE_REGULAR_TAG)) {
                return null;
            }

            if (!userCan(UserPermission.UPDATE_CATEGORIES) && !userCan(UserPermission.DELETE_CATEGORIES)) {
                return null;
            }

            return (
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="ghost" size="sm">
                            <MoreHorizontalIcon />
                            <span className="sr-only">{t('Actions')}</span>
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent>
                        {userCan(UserPermission.UPDATE_CATEGORIES) && (
                            <CategoryForm category={category}>
                                <DropdownMenuItem onSelect={(e) => e.preventDefault()}>
                                    <Edit2Icon className="size-3" />
                                    <span>{t('Edit')}</span>
                                </DropdownMenuItem>
                            </CategoryForm>
                        )}

                        {userCan(UserPermission.DELETE_CATEGORIES) && (
                            <DropdownMenuItem
                                variant="destructive"
                                onClick={() => {
                                    openConfirmation({
                                        title: t('Are you sure you want to delete this category?'),
                                        description:
                                            (category.isRegular ? t('This is marked as regular.') + '\n' : '') + t('This action cannot be undone.'),
                                        actionLabel: t('Delete'),
                                        actionVariant: 'destructive',
                                        cancelLabel: t('Cancel'),
                                        onAction: () => {
                                            router.delete(route('categories.destroy', category.id), {
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
                        )}
                    </DropdownMenuContent>
                </DropdownMenu>
            );
        },
    },
];
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: route('categories.index'),
    },
];
interface IndexPageProps {
    categories: Tag[];
}
export default function Index({ categories }: IndexPageProps) {
    const { t } = useLaravelReactI18n();
    return (
        <AppLayout breadcrumbs={breadcrumbs} title={t('Categories')}>
            <PageTitle>{t('Categories')}</PageTitle>
            <div className="mx-auto w-full max-w-3xl">
                <DataTable
                    headerButton={
                        userCan(UserPermission.CREATE_CATEGORIES) && (
                            <CategoryForm>
                                <Button>{t('Add category')}</Button>
                            </CategoryForm>
                        )
                    }
                    columns={columns}
                    data={categories}
                    rowId="id"
                />
            </div>
        </AppLayout>
    );
}

function CategoryForm({ category, children }: { category?: Tag; children: React.ReactNode }) {
    const [open, setOpen] = useState(false);
    const { t } = useLaravelReactI18n();
    const { data, setData, post, put, errors, reset, processing } = useForm({
        name: category?.nameTranslations ?? emptyTranslations(),
        is_regular: category?.isRegular ?? false,
    });

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();
        if (category) {
            put(route('categories.update', category.id), {
                onSuccess: () => {
                    setOpen(false);
                },
            });
        } else {
            post(route('categories.store'), {
                onSuccess: () => {
                    setOpen(false);
                    reset();
                },
            });
        }
    }

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>{children}</DialogTrigger>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{category ? t('Edit Category') : t('Add Category')}</DialogTitle>
                    <DialogDescription hidden></DialogDescription>
                </DialogHeader>

                <form className="space-y-4" onSubmit={handleSubmit}>
                    <TranslatableInput
                        label={t('Name')}
                        values={data.name}
                        onChange={(locale, value) => setData(`name`, { ...data.name, [locale]: value })}
                        errors={{ errors, name: 'name' }}
                    />
                    <SwitchField
                        description={t('Only admins would be allowed to edit and delete this category')}
                        label={t('Mark this category as regular')}
                        value={data.is_regular}
                        onChange={(value) => setData('is_regular', value)}
                        error={errors.is_regular}
                    />
                    <div className="flex justify-end">
                        <SubmitButton isSubmitting={processing}>{t('Save')}</SubmitButton>
                    </div>
                </form>
            </DialogContent>
        </Dialog>
    );
}
