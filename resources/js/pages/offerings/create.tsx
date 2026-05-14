import OfferingController from '@/actions/App/Http/Controllers/OfferingController';
import { Form } from '@/components/forms/Form';
import { ComboboxField } from '@/components/forms/inputs/ComboboxField';
import { CurrencyField } from '@/components/forms/inputs/CurrencyField';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { MultipleComboboxField } from '@/components/forms/inputs/MultipleComboboxField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { useLocaleDate } from '@/hooks/use-locale-date';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, type SelectOption, type SelectOptionWithModel } from '@/types';
import { useForm } from '@inertiajs/react';
import { formatDate } from 'date-fns';
import { TrashIcon } from 'lucide-react';
import { useTranslation } from 'react-i18next';

interface CreatePageProps {
  walletsOptions: SelectOption[];
  paymentMethods: SelectOption[];
  membersOptions: SelectOption[];
  missionariesOptions: SelectOptionWithModel;
  offeringTypesOptions: SelectOptionWithModel;
}

interface CreateForm {
  donor_id: string;
  date: string;
  offerings: {
    payment_method: string;
    offering_type: {
      id: string;
      model: string;
    };
    wallet_id: string;
    amount: string;
    note: string;
  }[];
}

export default function Create({ walletsOptions, paymentMethods, membersOptions, missionariesOptions, offeringTypesOptions }: CreatePageProps) {
  const { t: tPages } = useTranslation('pages');
  const { maxDate } = useLocaleDate();
  const { data, setData, submit, errors, processing } = useForm<Required<CreateForm>>({
    date: formatDate(new Date(), 'yyyy-MM-dd'),
    donor_id: '',
    offerings: [
      {
        wallet_id: walletsOptions[0]?.value.toString() ?? '',
        payment_method: paymentMethods[0]?.value.toString() ?? '',
        offering_type: {
          id: offeringTypesOptions.options[0]?.value.toString() ?? '',
          model: offeringTypesOptions.model ?? '',
        },
        amount: '',
        note: '',
      },
    ],
  });

  function handleSubmit() {
    submit(OfferingController.store(), { preserveScroll: true });
  }

  function handleAddOffering() {
    setData('offerings', [
      ...data.offerings,
      {
        wallet_id: walletsOptions[0]?.value.toString() ?? '',
        payment_method: paymentMethods[0]?.value.toString() ?? '',
        offering_type: {
          id: offeringTypesOptions?.options[0]?.value.toString() ?? '',
          model: offeringTypesOptions?.model ?? '',
        },
        amount: '',
        note: '',
      },
    ]);
  }

  function handleRemoveOffering(index: number) {
    const updatedOfferings = [...data.offerings];
    updatedOfferings.splice(index, 1);
    setData('offerings', updatedOfferings);
  }

  function handleUpdateOffering(index: number, field: string, value: unknown) {
    const updatedOfferings = [...data.offerings];
    if (updatedOfferings[index] === undefined) {
      return;
    }
    updatedOfferings[index] = {
      ...updatedOfferings[index],
      [field]: value,
    };
    setData('offerings', updatedOfferings);
  }

  const breadcrumbs: BreadcrumbItem[] = [
    {
      title: tPages(($) => $.offerings.create.offerings),
      href: OfferingController.index().url,
    },
    {
      title: tPages(($) => $.offerings.create.newModel, { model: tPages(($) => $.offerings.create.offering) }),
    },
  ];
  return (
    <AppLayout title={tPages(($) => $.offerings.create.offerings)} breadcrumbs={breadcrumbs}>
      <PageTitle>{tPages(($) => $.offerings.create.newModel, { model: tPages(($) => $.offerings.create.offering) })}</PageTitle>
      <div className="mt-2 flex items-center justify-center">
        <Form isSubmitting={processing} className="w-full max-w-2xl" onSubmit={handleSubmit}>
          <ComboboxField
            placeholder=""
            label={tPages(($) => $.offerings.create.whoIsThisOfferingFrom)}
            value={data.donor_id}
            onChange={(value) => setData('donor_id', value)}
            error={errors.donor_id}
            options={membersOptions}
          />
          <FieldsGrid>
            <DateField
              maxDate={maxDate()}
              required
              label={tPages(($) => $.offerings.create.dateOfOffering)}
              value={data.date}
              onChange={(value) => value && setData('date', value)}
              error={errors.date}
            />
          </FieldsGrid>

          <Button size="sm" variant="secondary" type="button" onClick={handleAddOffering}>
            {tPages(($) => $.offerings.create.addModel, { model: tPages(($) => $.offerings.create.offering) })}
          </Button>

          <div className="space-y-4 py-2">
            {data.offerings.map((offering, index) => (
              <fieldset className="space-y-2" key={index}>
                {data.offerings.length > 1 && (
                  <legend className="px-2 text-sm font-semibold">
                    <Button size="icon" className="size-6" variant="destructive" type="button" onClick={() => handleRemoveOffering(index)}>
                      <TrashIcon className="size-4" />
                    </Button>
                  </legend>
                )}
                <FieldsGrid className="grow">
                  <SelectField
                    required
                    label={tPages(($) => $.offerings.create.wallet)}
                    value={offering.wallet_id}
                    onValueChange={(value) => {
                      handleUpdateOffering(index, 'wallet_id', value);
                    }}
                    error={errors[`offerings.${index}.wallet_id` as keyof typeof data]}
                    options={walletsOptions}
                  />
                  <SelectField
                    required
                    label={tPages(($) => $.offerings.create.paymentMethod)}
                    value={offering.payment_method}
                    onValueChange={(value) => {
                      handleUpdateOffering(index, 'payment_method', value);
                    }}
                    error={errors[`offerings.${index}.payment_method` as keyof typeof data]}
                    options={paymentMethods}
                  />
                </FieldsGrid>
                <FieldsGrid className="grow">
                  <MultipleComboboxField
                    required
                    label={tPages(($) => $.offerings.create.offeringType)}
                    value={offering.offering_type}
                    onChange={(value) => {
                      handleUpdateOffering(index, 'offering_type', value);
                    }}
                    error={errors[`offerings.${index}.offering_type` as keyof typeof data]}
                    data={[offeringTypesOptions, missionariesOptions]}
                  />

                  <CurrencyField
                    required
                    label={tPages(($) => $.offerings.create.amount)}
                    value={offering.amount}
                    onChange={(value) => {
                      handleUpdateOffering(index, 'amount', value);
                    }}
                    error={errors[`offerings.${index}.amount` as keyof typeof data]}
                  />
                </FieldsGrid>
                <InputField
                  label={tPages(($) => $.offerings.create.note)}
                  value={offering.note}
                  onChange={(value) => {
                    handleUpdateOffering(index, 'note', value);
                  }}
                  error={errors[`offerings.${index}.note` as keyof typeof data]}
                />
              </fieldset>
            ))}
          </div>
        </Form>
      </div>
    </AppLayout>
  );
}
