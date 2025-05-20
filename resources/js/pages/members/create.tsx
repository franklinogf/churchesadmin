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
import { CivilStatus, Gender } from '@/enums';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import { getMultiselecOptionsLabels } from '@/lib/mutliselect';
import type { BreadcrumbItem, SelectOption } from '@/types';
import { type AddressFormData } from '@/types/models/address';
import { type MemberFormData } from '@/types/models/member';
import type { Tag } from '@/types/models/tag';
import { useForm } from '@inertiajs/react';

type CreateForm = MemberFormData & {
  address: AddressFormData;
};

interface CreatePageProps {
  genders: SelectOption[];
  civilStatuses: SelectOption[];
  skills: Tag[];
  categories: Tag[];
}
export default function Create({ genders, civilStatuses, skills, categories }: CreatePageProps) {
  const { t } = useTranslations();
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

  const breadcrumbs: BreadcrumbItem[] = [
    {
      title: t('Members'),
      href: route('members.index'),
    },
    {
      title: t('Add :model', { model: t('Member') }),
    },
  ];
  return (
    <AppLayout breadcrumbs={breadcrumbs} title={t('Members')}>
      <PageTitle>{t('Add :model', { model: t('Member') })}</PageTitle>
      <div className="mt-2 flex items-center justify-center">
        <Form isSubmitting={processing} className="w-full max-w-2xl" onSubmit={handleSubmit}>
          <InputField required label="Name" value={data.name} onChange={(value) => setData('name', value)} error={errors.name} />
          <InputField required label="Last Name" value={data.last_name} onChange={(value) => setData('last_name', value)} error={errors.last_name} />
          <FieldsGrid>
            <InputField required label="Email" type="email" value={data.email} onChange={(value) => setData('email', value)} error={errors.email} />
            <PhoneField required label="Phone" value={data.phone} onChange={(value) => setData('phone', value)} error={errors.phone} />
          </FieldsGrid>

          <DateField label="Date of Birth" value={data.dob} onChange={(value) => setData('dob', value)} error={errors.dob} />

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

          <AddressFormSkeleton data={data.address} setData={(value) => setData('address', value)} errors={errors} errorsName="address" />
        </Form>
      </div>
    </AppLayout>
  );
}
