import { AddressFormSkeleton } from '@/components/forms/AddressFormSkeleton';
import { Form } from '@/components/forms/Form';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { MultiSelectField } from '@/components/forms/inputs/MultiSelectField';
import { PhoneField } from '@/components/forms/inputs/PhoneField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { convertTagsToMultiselectOptions, getMultiselecOptionsLabels } from '@/lib/mutliselect';
import type { BreadcrumbItem, SelectOption } from '@/types';
import { AddressFormData } from '@/types/models/address';
import { Member, MemberFormData } from '@/types/models/member';
import { Tag } from '@/types/models/tag';
import { useForm } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Members',
        href: route('members.index'),
    },
    {
        title: 'Edit Member',
        href: '',
    },
];

type EditForm = MemberFormData & {
    address: AddressFormData;
};

interface EditPageProps {
    genders: SelectOption[];
    civilStatuses: SelectOption[];
    skills: Tag[];
    categories: Tag[];
    member: Member;
}
export default function Edit({ member, genders, civilStatuses, skills, categories }: EditPageProps) {
    const { t } = useLaravelReactI18n();
    const { data, setData, put, errors, processing, transform } = useForm<EditForm>({
        name: member.name,
        last_name: member.lastName,
        email: member.email,
        phone: member.phone,
        dob: member.dob,
        gender: member.gender,
        civil_status: member.civilStatus,
        skills: convertTagsToMultiselectOptions(member.skills),
        categories: convertTagsToMultiselectOptions(member.categories),
        address: {
            address_1: member.address?.address1 ?? '',
            address_2: member.address?.address2 ?? '',
            city: member.address?.city ?? '',
            state: member.address?.state ?? '',
            country: member.address?.country ?? '',
            zip_code: member.address?.zipCode ?? '',
        },
    });

    transform((data) => ({
        ...data,
        skills: getMultiselecOptionsLabels(data.skills),
        categories: getMultiselecOptionsLabels(data.categories),
    }));

    const handleSubmit = () => {
        put(route('members.update', member.id));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs} title={t('Members')}>
            <PageTitle>{t('Edit Member')}</PageTitle>
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
                    <Separator className="my-8" />

                    <AddressFormSkeleton
                        data={data.address}
                        setData={(value) => {
                            setData('address', value);
                        }}
                        errors={errors}
                        errorsName="address"
                    />
                </Form>
            </div>
        </AppLayout>
    );
}
