import { AddressFormSkeleton } from '@/components/forms/AddressFormSkeleton';
import { Form } from '@/components/forms/Form';
import { DatetimeField } from '@/components/forms/inputs/DatetimeField';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { MultiSelectField } from '@/components/forms/inputs/MultiSelectField';
import { PhoneField } from '@/components/forms/inputs/PhoneField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import { Separator } from '@/components/ui/separator';
import { CivilStatus } from '@/enums/CivilStatus';
import { Gender } from '@/enums/Gender';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import { getMultiselecOptionsLabels } from '@/lib/mutliselect';
import type { BreadcrumbItem, SelectOption } from '@/types';
import { type AddressFormData } from '@/types/models/address';
import { type MemberFormData } from '@/types/models/member';
import type { Tag } from '@/types/models/tag';
import type { Visit } from '@/types/models/visit';
import { useForm } from '@inertiajs/react';
import { useMemo } from 'react';

type CreateForm = MemberFormData & {
  address: AddressFormData;
  visit_id: string | null;
};

interface CreatePageProps {
  genders: SelectOption[];
  civilStatuses: SelectOption[];
  skills: Tag[];
  categories: Tag[];
  visit: Visit | null;
}
export default function Create({ genders, civilStatuses, skills, categories, visit }: CreatePageProps) {
  const { t } = useTranslations();
  const { data, setData, post, errors, processing, transform } = useForm<CreateForm>({
    visit_id: visit?.id.toString() || null,
    name: visit?.name || '',
    last_name: visit?.lastName || '',
    email: visit?.email || '',
    phone: visit?.phone || '',
    dob: '',
    gender: Gender.MALE,
    civil_status: CivilStatus.SINGLE,
    skills: [],
    categories: [],
    address: {
      address_1: visit?.address?.address1 || '',
      address_2: visit?.address?.address2 || '',
      city: visit?.address?.city || '',
      state: visit?.address?.state || '',
      country: visit?.address?.country || '',
      zip_code: visit?.address?.zipCode || '',
    },
  });

  transform((data) => ({
    ...data,
    skills: getMultiselecOptionsLabels(data.skills),
    categories: getMultiselecOptionsLabels(data.categories),
  }));

  const handleSubmit = () => {
    post(route('members.store'), { preserveScroll: true });
  };

  const breadcrumbs: BreadcrumbItem[] = useMemo(
    () =>
      visit
        ? [
            {
              title: t('Visits'),
              href: route('visits.index'),
            },
            { title: visit.name, href: route('visits.follow-ups.index', visit.id) },
            { title: t('Transfer to member') },
          ]
        : [
            {
              title: t('Members'),
              href: route('members.index'),
            },
            {
              title: t('Add :model', { model: t('Member') }),
            },
          ],
    [t, visit],
  );

  return (
    <AppLayout breadcrumbs={breadcrumbs} title={t('Members')}>
      <PageTitle>{t('Add :model', { model: t('Member') })}</PageTitle>
      <div className="mt-2 flex items-center justify-center">
        <Form isSubmitting={processing} className="w-full max-w-2xl" onSubmit={handleSubmit}>
          <FieldError error={errors.visit_id} />
          <InputField required label={t('Name')} value={data.name} onChange={(value) => setData('name', value)} error={errors.name} />
          <InputField
            required
            label={t('Last Name')}
            value={data.last_name}
            onChange={(value) => setData('last_name', value)}
            error={errors.last_name}
          />
          <FieldsGrid>
            <InputField label={t('Email')} type="email" value={data.email} onChange={(value) => setData('email', value)} error={errors.email} />
            <PhoneField label={t('Phone')} value={data.phone} onChange={(value) => setData('phone', value)} error={errors.phone} />
          </FieldsGrid>

          <DatetimeField
            hideTime
            max={new Date()}
            label={t('Date of birth')}
            value={data.dob}
            onChange={(value) => setData('dob', value)}
            error={errors.dob}
          />

          <FieldsGrid>
            <SelectField
              required
              label={t('Gender')}
              value={data.gender}
              onChange={(value) => setData('gender', value)}
              options={genders}
              error={errors.gender}
            />
            <SelectField
              required
              label={t('Civil status')}
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
