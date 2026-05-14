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
import { useForm } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

interface CreatePageProps {
  genders: SelectOption[];
  offeringFrequencies: SelectOption[];
}

type CreateForm = {
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
export default function Create({ genders, offeringFrequencies }: CreatePageProps) {
  const { t: tPages } = useTranslation('pages');
  const { data, setData, submit, errors, processing } = useForm<CreateForm>({
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
    submit(MissionaryController.store(), { preserveScroll: true });
  };

  const breadcrumbs: BreadcrumbItem[] = [
    {
      title: tPages(($) => $.main.missionaries.create.missionaries),
      href: MissionaryController.index().url,
    },
    {
      title: tPages(($) => $.main.missionaries.create.createModel, { model: tPages(($) => $.main.missionaries.create.missionary) }),
    },
  ];

  return (
    <AppLayout breadcrumbs={breadcrumbs} title={tPages(($) => $.main.missionaries.create.missionaries)}>
      <PageTitle>{tPages(($) => $.main.missionaries.create.addModel, { model: tPages(($) => $.main.missionaries.create.missionary) })}</PageTitle>
      <div className="mt-2 flex items-center justify-center">
        <Form isSubmitting={processing} className="w-full max-w-2xl" onSubmit={handleSubmit}>
          <InputField
            required
            label={tPages(($) => $.main.missionaries.create.name)}
            value={data.name}
            onChange={(value) => setData('name', value)}
            error={errors.name}
          />
          <InputField
            required
            label={tPages(($) => $.main.missionaries.create.lastName)}
            value={data.last_name}
            onChange={(value) => setData('last_name', value)}
            error={errors.last_name}
          />
          <FieldsGrid>
            <InputField
              label={tPages(($) => $.main.missionaries.create.email)}
              type="email"
              value={data.email}
              onChange={(value) => setData('email', value)}
              error={errors.email}
            />
            <PhoneField
              label={tPages(($) => $.main.missionaries.create.phone)}
              value={data.phone}
              onChange={(value) => setData('phone', value)}
              error={errors.phone}
            />
          </FieldsGrid>
          <FieldsGrid>
            <SelectField
              required
              label={tPages(($) => $.main.missionaries.create.gender)}
              value={data.gender}
              onValueChange={(value) => setData('gender', value)}
              options={genders}
              error={errors.gender}
            />
          </FieldsGrid>
          <InputField
            label={tPages(($) => $.main.missionaries.create.church)}
            value={data.church}
            onChange={(value) => setData('church', value)}
            error={errors.church}
          />
          <FieldsGrid>
            <CurrencyField
              placeholder="0.00"
              label={tPages(($) => $.main.missionaries.create.offering)}
              value={data.offering}
              onValueChange={(value) => value !== undefined && setData('offering', value)}
              error={errors.offering}
            />
            <SelectField
              clearable
              label={tPages(($) => $.main.missionaries.create.offeringFrequency)}
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
