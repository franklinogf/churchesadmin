import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { InputField } from '@/components/forms/inputs/InputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader } from '@/components/ui/dialog';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import AppLayout from '@/layouts/app-layout';
import useConfirmationStore from '@/stores/confirmationStore';
import { Tag } from '@/types/models/tags';
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
            return (
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="ghost" size="sm">
                            <MoreHorizontalIcon />
                            <span className="sr-only">{t('Actions')}</span>
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent>
                        <SkillForm skill={row.original}>
                            <DropdownMenuItem onSelect={(e) => e.preventDefault()}>
                                <Edit2Icon className="size-3" />
                                <span>{t('Edit')}</span>
                            </DropdownMenuItem>
                        </SkillForm>
                        <DropdownMenuItem
                            variant="destructive"
                            onClick={() => {
                                openConfirmation({
                                    title: t('Are you sure you want to delete this skill?'),
                                    description: t('This action cannot be undone.'),
                                    actionLabel: t('Delete'),
                                    actionVariant: 'destructive',
                                    cancelLabel: t('Cancel'),
                                    onAction: () => {
                                        router.delete(route('skills.destroy', row.original.id), {
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
            <DataTable
                headerButton={
                    <SkillForm>
                        <Button>{t('Add skill')}</Button>
                    </SkillForm>
                }
                columns={columns}
                data={skills}
                rowId="id"
            />
        </AppLayout>
    );
}

function SkillForm({ skill, children }: { skill?: Tag; children: React.ReactNode }) {
    const [open, setOpen] = useState(false);
    const { t } = useLaravelReactI18n();
    const { data, setData, post, put, errors, reset } = useForm({
        name: skill?.name ?? '',
    });

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();
        if (skill) {
            put(route('skills.update', skill.id), {
                onSuccess: () => {
                    setOpen(false);
                },
            });
        } else {
            post(route('skills.store'), {
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
                    <DialogTitle>{t('Add Skill')}</DialogTitle>
                    <DialogDescription>{t('Add a new skill.')}</DialogDescription>
                </DialogHeader>

                <form className="space-y-4" onSubmit={handleSubmit}>
                    <InputField
                        label="Name"
                        placeholder="Enter skill name"
                        required
                        value={data.name}
                        onChange={(value) => setData('name', value)}
                        error={errors.name}
                    />
                    <div className="flex justify-end">
                        <SubmitButton>{t('Save')}</SubmitButton>
                    </div>
                </form>
            </DialogContent>
        </Dialog>
    );
}
