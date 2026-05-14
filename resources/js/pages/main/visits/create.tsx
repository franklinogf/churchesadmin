import VisitController from '@/actions/App/Http/Controllers/VisitController';
import { AddressFormSkeleton } from '@/components/forms/AddressFormSkeleton';
import { Form } from '@/components/forms/Form';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { PhoneField } from '@/components/forms/inputs/PhoneField';
import { PageTitle } from '@/components/PageTitle';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import type { AddressFormData } from '@/types/models/address';
import { useForm } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

type CreateForm = {
  name: string;
  last_name: string;
  phone: string;
  email: string;
  first_visit_date: string | null;
  address: AddressFormData;
};
export default function VisitsCreate() {
  const { t: tPages } = useTranslation('pages');
  const { data, setData, submit, errors, processing } = useForm<CreateForm>({
    name: '',
    last_name: '',
    email: '',
    phone: '',
    first_visit_date: '',
    address: {
      address_1: '',
      address_2: '',
      city: '',
      state: '',
      country: '',
      zip_code: '',
    },
  });

  function handleSubmit() {
    submit(VisitController.store(), { preserveScroll: true });
  }

  return (
    <AppLayout
      title={tPages(($) => $.main.visits.create.addModel, { model: tPages(($) => $.main.visits.create.visit) })}
      breadcrumbs={[
        { title: tPages(($) => $.main.visits.create.visits), href: VisitController.index().url },
        { title: tPages(($) => $.main.visits.create.addModel, { model: tPages(($) => $.main.visits.create.visit) }) },
      ]}
    >
      <PageTitle>{tPages(($) => $.main.visits.create.addModel, { model: tPages(($) => $.main.visits.create.visit) })}</PageTitle>

      <Form className="mx-auto mt-6 w-full max-w-2xl" onSubmit={handleSubmit} isSubmitting={processing}>
        <InputField
          label={tPages(($) => $.main.visits.create.name)}
          value={data.name}
          onChange={(value) => setData('name', value)}
          error={errors.name}
          required
        />
        <InputField
          label={tPages(($) => $.main.visits.create.lastName)}
          value={data.last_name}
          onChange={(value) => setData('last_name', value)}
          error={errors.last_name}
          required
        />
        <FieldsGrid>
          <PhoneField
            label={tPages(($) => $.main.visits.create.phone)}
            value={data.phone}
            onChange={(value) => setData('phone', value)}
            error={errors.phone}
          />
          <InputField
            label={tPages(($) => $.main.visits.create.email)}
            type="email"
            value={data.email}
            onChange={(value) => setData('email', value)}
            error={errors.email}
          />
        </FieldsGrid>

        <DateField
          maxDate="today"
          label={tPages(($) => $.main.visits.create.firstVisitDate)}
          value={data.first_visit_date}
          onChange={(value) => setData('first_visit_date', value)}
          error={errors.first_visit_date}
        />
        <Separator className="my-8" />

        <AddressFormSkeleton data={data.address} setData={(value) => setData('address', value)} errors={errors} />
      </Form>
    </AppLayout>
  );
}
