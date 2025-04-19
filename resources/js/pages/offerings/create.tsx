import { Form } from '@/components/forms/Form';
import { ComboboxField } from '@/components/forms/inputs/ComboboxField';
import { CurrencyField } from '@/components/forms/inputs/CurrencyField';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { TextareaField } from '@/components/forms/inputs/TextareaField';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, SelectOption } from '@/types';
import { useForm } from '@inertiajs/react';
import { formatDate } from 'date-fns';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { TrashIcon } from 'lucide-react';

interface CreatePageProps {
  wallets: SelectOption[];
  offeringTypes: SelectOption[];
  members: SelectOption[];
}

interface CreateForm {
  date: string;
  offering_type: string;
  payer_id: string;
  message: string;
  offerings: {
    wallet_id: string;
    amount: string;
  }[];
}
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Offerings',
    href: route('offerings.index'),
  },
  {
    title: 'New Offering',
  },
];
export default function Create({ wallets, offeringTypes, members }: CreatePageProps) {
  const { t } = useLaravelReactI18n();
  const { data, setData, post, errors, processing } = useForm<Required<CreateForm>>({
    date: formatDate(new Date(), 'yyyy-MM-dd'),
    offering_type: offeringTypes[0].value.toString(),
    payer_id: '',
    message: '',
    offerings: [
      {
        wallet_id: wallets[0].value.toString(),
        amount: '0.00',
      },
    ],
  });

  function handleSubmit() {
    post(route('offerings.store'));
  }

  function handleAddOffering() {
    setData('offerings', [
      ...data.offerings,
      {
        wallet_id: wallets[0].value.toString(),
        amount: '0.00',
      },
    ]);
  }

  function handleRemoveOffering(index: number) {
    const updatedOfferings = [...data.offerings];
    updatedOfferings.splice(index, 1);
    setData('offerings', updatedOfferings);
  }

  return (
    <AppLayout title={t('Offerings')} breadcrumbs={breadcrumbs}>
      <PageTitle>{t('New Offering')}</PageTitle>
      <div className="mt-2 flex items-center justify-center">
        <Form isSubmitting={processing} className="w-full max-w-2xl" onSubmit={handleSubmit}>
          <FieldsGrid>
            <DateField required label={t('Date of Offering')} value={data.date} onChange={(value) => setData('date', value)} error={errors.date} />
            <SelectField
              required
              label={t('Type')}
              value={data.offering_type}
              onChange={(value) => setData('offering_type', value)}
              error={errors.offering_type}
              options={offeringTypes}
            />
          </FieldsGrid>
          <ComboboxField
            required
            label={t('Who is this offering from?')}
            value={data.payer_id}
            onChange={(value) => setData('payer_id', value)}
            error={errors.payer_id}
            options={members}
          />

          <Button size="sm" variant="secondary" type="button" onClick={handleAddOffering}>
            {t('Add offering')}
          </Button>

          {data.offerings.map((offering, index) => (
            <div className="flex items-center gap-2" key={index}>
              <FieldsGrid className="grow">
                <SelectField
                  required
                  label={t('Wallet')}
                  value={offering.wallet_id}
                  onChange={(value) => {
                    const updatedOfferings = [...data.offerings];
                    updatedOfferings[index] = {
                      ...updatedOfferings[index],
                      wallet_id: value,
                    };
                    setData('offerings', updatedOfferings);
                  }}
                  error={errors[`offerings.${index}.wallet_id` as keyof typeof data]}
                  options={wallets}
                />

                <CurrencyField
                  required
                  label={t('Amount')}
                  value={offering.amount}
                  onChange={(value) => {
                    const updatedOfferings = [...data.offerings];
                    updatedOfferings[index] = {
                      ...updatedOfferings[index],
                      amount: value,
                    };
                    setData('offerings', updatedOfferings);
                  }}
                  error={errors[`offerings.${index}.amount` as keyof typeof data]}
                />
              </FieldsGrid>
              {data.offerings.length > 1 && (
                <Button className="mt-3" size="icon" variant="destructive" type="button" onClick={() => handleRemoveOffering(index)}>
                  <TrashIcon className="h-4 w-4" />
                </Button>
              )}
            </div>
          ))}

          <TextareaField label={t('Message')} value={data.message} onChange={(value) => setData('message', value)} error={errors.message} />
        </Form>
      </div>
    </AppLayout>
  );
}
