import MissionaryController from '@/actions/App/Http/Controllers/MissionaryController';
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
import { type Missionary } from '@/types/models/missionary';
import { useForm } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

type EditForm = {
  name: string;
  last_name: string;
  email: string;
  phone: string;
  gender: string;
  church: string;
  offering: string;
  offering_frequency: string;
  address: {
    address_1: string;
    address_2: string;
    city: string;
    state: string;
    country: string;
    zip_code: string;
  };
};
interface EditPageProps {
  genders: SelectOption[];
  missionary: Missionary;
  offeringFrequencies: SelectOption[];
}

export default function Edit({ genders, missionary, offeringFrequencies }: EditPageProps) {
  const { t: tPages } = useTranslation('pages');
  const { data, setData, submit, errors, processing } = useForm<EditForm>({
    name: missionary.name,
    last_name: missionary.lastName,
    email: missionary.email || '',
    phone: missionary.phone || '',
    gender: missionary.gender,
    church: missionary.church || '',
    offering: missionary.offering?.toString() || '',
    offering_frequency: missionary.offeringFrequency || '',
    address: {
      address_1: missionary.address?.address1 ?? '',
      address_2: missionary.address?.address2 ?? '',
      city: missionary.address?.city ?? '',
      state: missionary.address?.state ?? '',
      country: missionary.address?.country ?? '',
      zip_code: missionary.address?.zipCode ?? '',
    },
  });
  const handleSubmit = () => {
    submit(MissionaryController.update(missionary.id), { preserveScroll: true });
  };

  const breadcrumbs: BreadcrumbItem[] = [
    {
      title: tPages(($) => $.main.missionaries.edit.missionaries),
      href: MissionaryController.index().url,
    },
    {
      title: tPages(($) => $.main.missionaries.edit.editModel, { model: tPages(($) => $.main.missionaries.edit.missionary) }),
    },
  ];

  return (
    <AppLayout breadcrumbs={breadcrumbs} title={tPages(($) => $.main.missionaries.edit.missionaries)}>
      <PageTitle>{tPages(($) => $.main.missionaries.edit.editModel, { model: tPages(($) => $.main.missionaries.edit.missionary) })}</PageTitle>
      <div className="mt-2 flex items-center justify-center">
        <Form isSubmitting={processing} className="w-full max-w-2xl" onSubmit={handleSubmit}>
          <InputField
            required
            label={tPages(($) => $.main.missionaries.edit.name)}
            value={data.name}
            onChange={(value) => setData('name', value)}
            error={errors.name}
          />
          <InputField
            required
            label={tPages(($) => $.main.missionaries.edit.lastName)}
            value={data.last_name}
            onChange={(value) => setData('last_name', value)}
            error={errors.last_name}
          />
          <FieldsGrid>
            <InputField
              label={tPages(($) => $.main.missionaries.edit.email)}
              type="email"
              value={data.email}
              onChange={(value) => setData('email', value)}
              error={errors.email}
            />
            <PhoneField
              label={tPages(($) => $.main.missionaries.edit.phone)}
              value={data.phone}
              onChange={(value) => setData('phone', value)}
              error={errors.phone}
            />
          </FieldsGrid>
          <FieldsGrid>
            <SelectField
              required
              label={tPages(($) => $.main.missionaries.edit.gender)}
              value={data.gender}
              onValueChange={(value) => setData('gender', value)}
              options={genders}
              error={errors.gender}
            />
          </FieldsGrid>
          <InputField
            label={tPages(($) => $.main.missionaries.edit.church)}
            value={data.church}
            onChange={(value) => setData('church', value)}
            error={errors.church}
          />
          <FieldsGrid>
            <CurrencyField
              placeholder="0.00"
              label={tPages(($) => $.main.missionaries.edit.offering)}
              value={data.offering}
              onValueChange={(value) => value !== undefined && setData('offering', value)}
              error={errors.offering}
            />
            <SelectField
              clearable
              label={tPages(($) => $.main.missionaries.edit.offeringFrequency)}
              value={data.offering_frequency}
              onValueChange={(value) => setData('offering_frequency', value)}
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
