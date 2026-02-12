import MemberController from '@/actions/App/Http/Controllers/MemberController';
import VisitController from '@/actions/App/Http/Controllers/VisitController';
import VisitFollowUpController from '@/actions/App/Http/Controllers/VisitFollowUpController';
import type { Option } from '@/components/custom-ui/MultiSelect';
import { AddressFormSkeleton } from '@/components/forms/AddressFormSkeleton';
import { Form } from '@/components/forms/Form';
import { DateField } from '@/components/forms/inputs/DateField';
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
import type { Tag } from '@/types/models/tag';
import type { Visit } from '@/types/models/visit';
import { useForm } from '@inertiajs/react';
import { useMemo } from 'react';

type CreateForm = {
  name: string;
  last_name: string;
  email: string;
  phone: string;
  dob: string | null;
  baptism_date: string | null;
  gender: string;
  civil_status: string;
  skills: Option[];
  categories: Option[];
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
  const { data, setData, submit, errors, processing, transform } = useForm<CreateForm>({
    visit_id: visit?.id.toString() || null,
    name: visit?.name || '',
    last_name: visit?.lastName || '',
    email: visit?.email || '',
    phone: visit?.phone || '',
    dob: null,
    baptism_date: null,
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
    submit(MemberController.store(), { preserveScroll: true });
  };

  const breadcrumbs: BreadcrumbItem[] = useMemo(
    () =>
      visit
        ? [
            {
              title: t('Visits'),
              href: VisitController.index().url,
            },
            { title: visit.name, href: VisitFollowUpController.index(visit.id).url },
            { title: t('Transfer to member') },
          ]
        : [
            {
              title: t('Members'),
              href: MemberController.index().url,
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

          <DateField maxDate="today" label={t('Date of birth')} value={data.dob} onChange={(value) => setData('dob', value)} error={errors.dob} />

          <DateField
            maxDate="today"
            label={t('Baptism date')}
            value={data.baptism_date}
            onChange={(value) => setData('baptism_date', value)}
            error={errors.baptism_date}
          />

          <FieldsGrid>
            <SelectField
              required
              label={t('Gender')}
              value={data.gender}
              onValueChange={(value) => setData('gender', value)}
              options={genders}
              error={errors.gender}
            />
            <SelectField
              required
              label={t('Civil status')}
              value={data.civil_status}
              onValueChange={(value) => setData('civil_status', value)}
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
