import { Option } from '@/components/custom-ui/MultiSelect';
import { Form } from '@/components/forms/Form';
import { CountryField } from '@/components/forms/inputs/CountryField';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { MultiSelectField } from '@/components/forms/inputs/MultiSelectField';
import { PhoneField } from '@/components/forms/inputs/PhoneField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import { Separator } from '@/components/ui/separator';
import { CivilStatus, Gender } from '@/enums';
import AppLayout from '@/layouts/app-layout';
import { getMultiselecOptionsLabels } from '@/lib/mutliselect';
import type { BreadcrumbItem, SelectOption } from '@/types';
import type { Tag } from '@/types/models/tag';
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
    address: {
        address_1: string;
        address_2: string;
        city: string;
        state: string;
        country: string;
        zip_code: string;
    };
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
        address: {
            address_1: '',
            address_2: '',
            city: '',
            state: '',
            country: '',
            zip_code: '',
        },
    });

    transform((data) => ({
        ...data,
        skills: getMultiselecOptionsLabels(data.skills),
        categories: getMultiselecOptionsLabels(data.categories),
    }));

    const handleSubmit = () => {
        post(route('members.store'));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs} title={t('Members')}>
            <PageTitle>{t('Add Member')}</PageTitle>
            <div className="mt-2 flex items-center justify-center">
                <Form isSubmitting={processing} className="w-full max-w-2xl" onSubmit={handleSubmit}>
                    <InputField required label="Name" value={data.name} onChange={(value) => setData('name', value)} error={errors.name} />
                    <InputField
                        required
                        label="Last Name"
                        value={data.last_name}
                        onChange={(value) => setData('last_name', value)}
                        error={errors.last_name}
                    />
                    <FieldsGrid>
                        <InputField
                            required
                            label="Email"
                            type="email"
                            value={data.email}
                            onChange={(value) => setData('email', value)}
                            error={errors.email}
                        />
                        <PhoneField required label="Phone" value={data.phone} onChange={(value) => setData('phone', value)} error={errors.phone} />
                    </FieldsGrid>

                    <DateField required label="Date of Birth" value={data.dob} onChange={(value) => setData('dob', value)} error={errors.dob} />

                    <FieldsGrid>
                        <SelectField
                            required
                            label="Gender"
                            value={data.gender}
                            onChange={(value) => setData('gender', value)}
                            options={genders}
                            error={errors.gender}
                        />
                        <SelectField
                            required
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
                    <Separator className="my-4" />
                    <div className="space-y-2">
                        <InputField
                            label="Address line 1"
                            value={data.address.address_1}
                            onChange={(value) => setData('address', { ...data.address, address_1: value })}
                            error={errors['address.address_1' as keyof CreateForm]}
                        />
                        <InputField
                            label="Address line 2"
                            value={data.address.address_2}
                            onChange={(value) => setData('address', { ...data.address, address_2: value })}
                            error={errors['address.address_2' as keyof CreateForm]}
                        />
                        <CountryField
                            label="Country"
                            value={data.address.country}
                            onChange={(country) => setData('address', { ...data.address, country })}
                            error={errors['address.country' as keyof CreateForm]}
                        />
                        <FieldsGrid cols={3}>
                            <InputField
                                label="City"
                                value={data.address.city}
                                onChange={(value) => setData('address', { ...data.address, city: value })}
                                error={errors['address.city' as keyof CreateForm]}
                            />
                            <InputField
                                label="State"
                                value={data.address.state}
                                onChange={(value) => setData('address', { ...data.address, state: value })}
                                error={errors['address.state' as keyof CreateForm]}
                            />
                            <InputField
                                label="Zip Code"
                                value={data.address.zip_code}
                                onChange={(value) => setData('address', { ...data.address, zip_code: value })}
                                error={errors['address.zip_code' as keyof CreateForm]}
                            />
                        </FieldsGrid>
                    </div>
                </Form>
            </div>
        </AppLayout>
    );
}
