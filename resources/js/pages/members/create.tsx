import { Option } from '@/components/custom-ui/MultiSelect';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { MultiSelectField } from '@/components/forms/inputs/MultiSelectField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { PageTitle } from '@/components/PageTitle';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import { CivilStatus, Gender } from '@/enums';
import AppLayout from '@/layouts/app-layout';
import { getMultiselecOptionsLabels } from '@/lib/mutliselect';
import type { BreadcrumbItem, SelectOption } from '@/types';
import type { Tag } from '@/types/models/tags';
import { useForm } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';

export type CreateForm = {
    name: string;
    last_name: string;
    email: string;
    phone: string;
    dob: string;
    gender: string;
    civil_status: string;
    skills: Option[];
    categories: Option[];
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Members',
        href: route('members.index'),
    },
    {
        title: 'Add Member',
        href: route('members.create'),
    },
];
interface CreatePageProps {
    genders: SelectOption[];
    civilStatuses: SelectOption[];
    skills: Tag[];
    categories: Tag[];
}
export default function Create({ genders, civilStatuses, skills, categories }: CreatePageProps) {
    const { t } = useLaravelReactI18n();
    const { data, setData, post, errors, processing, transform } = useForm<CreateForm>({
        name: '',
        last_name: '',
        email: '',
        phone: '',
        dob: '',
        gender: Gender.MALE,
        civil_status: CivilStatus.SINGLE,
        skills: [],
        categories: [],
    });

    transform((data) => ({
        ...data,
        skills: getMultiselecOptionsLabels(data.skills),
        categories: getMultiselecOptionsLabels(data.categories),
    }));

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('members.store'));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs} title={t('Members')}>
            <PageTitle>{t('Add Member')}</PageTitle>
            <div className="mt-2 flex items-center justify-center">
                <form className="w-full max-w-2xl" onSubmit={handleSubmit}>
                    <Card>
                        <CardContent className="space-y-4">
                            <InputField label="Name" value={data.name} onChange={(value) => setData('name', value)} error={errors.name} />
                            <InputField
                                label="Last Name"
                                value={data.last_name}
                                onChange={(value) => setData('last_name', value)}
                                error={errors.last_name}
                            />
                            <FieldsGrid>
                                <InputField label="Email" value={data.email} onChange={(value) => setData('email', value)} error={errors.email} />
                                <InputField label="Phone" value={data.phone} onChange={(value) => setData('phone', value)} error={errors.phone} />
                            </FieldsGrid>

                            <DateField label="Date of Birth" value={data.dob} onChange={(value) => setData('dob', value)} error={errors.dob} />

                            <FieldsGrid>
                                <SelectField
                                    label="Gender"
                                    value={data.gender}
                                    onChange={(value) => setData('gender', value)}
                                    options={genders}
                                    error={errors.gender}
                                />
                                <SelectField
                                    label="Civil Status"
                                    value={data.civil_status}
                                    onChange={(value) => setData('civil_status', value)}
                                    options={civilStatuses}
                                    error={errors.civil_status}
                                />
                            </FieldsGrid>

                            <FieldsGrid>
                                <MultiSelectField
                                    label={t('Skills')}
                                    value={data.skills}
                                    onChange={(value) => setData('skills', value)}
                                    options={skills}
                                    error={errors.skills}
                                />
                                <MultiSelectField
                                    label={t('Categories')}
                                    value={data.categories}
                                    onChange={(value) => setData('categories', value)}
                                    options={categories}
                                    error={errors.categories}
                                />
                            </FieldsGrid>
                        </CardContent>
                        <CardFooter className="flex justify-end">
                            <SubmitButton isSubmitting={processing}>{t('Save')}</SubmitButton>
                        </CardFooter>
                    </Card>
                </form>
            </div>
        </AppLayout>
    );
}
