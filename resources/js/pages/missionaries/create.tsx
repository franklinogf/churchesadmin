import { AddressFormSkeleton } from '@/components/forms/AddressFormSkeleton';
import { Form } from '@/components/forms/Form';
import { CurrencyField } from '@/components/forms/inputs/CurrencyField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { PhoneField } from '@/components/forms/inputs/PhoneField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem, SelectOption } from '@/types';
import { AddressFormData } from '@/types/models/address';
import { MissionaryFormData } from '@/types/models/missionary';
import { useForm } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Missionaries',
    href: route('missionaries.index'),
  },
  {
    title: 'Create Missionary',
    href: route('missionaries.store'),
  },
];

type CreateForm = MissionaryFormData & {
  address: AddressFormData;
};
interface CreatePageProps {
  genders: SelectOption[];
  offeringFrequencies: SelectOption[];
}
export default function Create({ genders, offeringFrequencies }: CreatePageProps) {
  const { t } = useLaravelReactI18n();

  const { data, setData, post, errors, processing } = useForm<CreateForm>({
    name: '',
    last_name: '',
    email: '',
    phone: '',
    gender: '',
    church: '',
    offering: '',
    offering_frequency: '',
    address: {
      address_1: '',
      address_2: '',
      city: '',
      state: '',
      country: '',
      zip_code: '',
    },
  });

  const handleSubmit = () => {
    post(route('missionaries.store'));
  };
  return (
    <AppLayout breadcrumbs={breadcrumbs} title={t('Missionaries')}>
      <PageTitle>{t('Add Missionary')}</PageTitle>
      <div className="mt-2 flex items-center justify-center">
        <Form isSubmitting={processing} className="w-full max-w-2xl" onSubmit={handleSubmit}>
          <InputField required label={t('Name')} value={data.name} onChange={(value) => setData('name', value)} error={errors.name} />
          <InputField
            required
            label={t('Last Name')}
            value={data.last_name}
            onChange={(value) => setData('last_name', value)}
            error={errors.last_name}
          />
          <FieldsGrid>
            <InputField
              required
              label={t('Email')}
              type="email"
              value={data.email}
              onChange={(value) => setData('email', value)}
              error={errors.email}
            />
            <PhoneField required label={t('Phone')} value={data.phone} onChange={(value) => setData('phone', value)} error={errors.phone} />
          </FieldsGrid>
          <FieldsGrid>
            <SelectField
              required
              label={t('Gender')}
              value={data.gender}
              onChange={(value) => setData('gender', value)}
              options={genders}
              error={errors.gender}
            />
          </FieldsGrid>
          <InputField required label={t('Church')} value={data.church} onChange={(value) => setData('church', value)} error={errors.church} />
          <FieldsGrid>
            <CurrencyField
              placeholder="0.00"
              required
              label={t('Offering')}
              value={data.offering}
              onChange={(value) => setData('offering', value)}
              error={errors.offering}
            />
            <SelectField
              required
              label={t('Offering Frequency')}
              value={data.offering_frequency}
              onChange={(value) => setData('offering_frequency', value)}
              options={offeringFrequencies}
              error={errors.offering_frequency}
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
