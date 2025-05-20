import { Form } from '@/components/forms/Form';
import { ComboboxField } from '@/components/forms/inputs/ComboboxField';
import { CurrencyField } from '@/components/forms/inputs/CurrencyField';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { MultipleComboboxField } from '@/components/forms/inputs/MultipleComboboxField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import { useLocaleDate } from '@/hooks/use-locale-date';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, type SelectOption, type SelectOptionWithModel } from '@/types';
import type { Offering } from '@/types/models/offering';
import { useForm } from '@inertiajs/react';
import { formatDate, parseISO } from 'date-fns';

interface EditPageProps {
  walletsOptions: SelectOption[];
  paymentMethods: SelectOption[];
  membersOptions: SelectOption[];
  missionariesOptions: SelectOptionWithModel;
  offeringTypesOptions: SelectOptionWithModel;
  offering: Offering;
}

interface CreateForm {
  donor_id: string;
  date: string;
  payment_method: string;
  offering_type: {
    id: string;
    model: string;
  };
  wallet_id: string;
  amount: string;
  note: string;
}

export default function Edit({ walletsOptions, paymentMethods, membersOptions, missionariesOptions, offeringTypesOptions, offering }: EditPageProps) {
  const { t } = useTranslations();
  const { formatLocaleDate } = useLocaleDate();

  const { data, setData, put, errors, processing } = useForm<Required<CreateForm>>({
    date: formatDate(parseISO(offering.date), 'yyyy-MM-dd'),
    donor_id: offering.donor?.id?.toString() ?? '',
    wallet_id: offering.transaction.wallet?.id.toString() ?? '',
    payment_method: offering.paymentMethod,
    offering_type: {
      id: offering.offeringType.id.toString(),
      model: offering.offeringTypeModel,
    },
    amount: offering.transaction.amountFloat,
    note: offering.note ?? '',
  });

  function handleSubmit() {
    put(route('offerings.update', offering.id));
  }

  const breadcrumbs: BreadcrumbItem[] = [
    {
      title: t('Offerings'),
      href: route('offerings.index'),
    },
    {
      title: formatLocaleDate(offering.date, { dateStyle: 'long' }) ?? '',
      href: route('offerings.index', { date: offering.date }),
    },
    {
      title: t('Edit :model', { model: t('Offering') }),
    },
  ];

  return (
    <AppLayout title={t('Offerings')} breadcrumbs={breadcrumbs}>
      <PageTitle>{t('Edit :model', { model: t('Offering') })}</PageTitle>
      <div className="mt-2 flex items-center justify-center">
        <Form isSubmitting={processing} className="w-full max-w-2xl" onSubmit={handleSubmit}>
          <ComboboxField
            placeholder=""
            label={t('Who is this offering from?')}
            value={data.donor_id}
            onChange={(value) => setData('donor_id', value)}
            error={errors.donor_id}
            options={membersOptions}
          />
          <FieldsGrid>
            <DateField required label={t('Date of Offering')} value={data.date} onChange={(value) => setData('date', value)} error={errors.date} />
          </FieldsGrid>

          <div className="space-y-4 py-2">
            <FieldsGrid className="grow">
              <SelectField
                required
                label={t('Wallet')}
                value={data.wallet_id}
                onChange={(value) => {
                  setData('wallet_id', value);
                }}
                error={errors.wallet_id}
                options={walletsOptions}
              />
              <SelectField
                required
                label={t('Payment method')}
                value={data.payment_method}
                onChange={(value) => {
                  setData('payment_method', value);
                }}
                error={errors.payment_method}
                options={paymentMethods}
              />
            </FieldsGrid>
            <FieldsGrid className="grow">
              <MultipleComboboxField
                required
                label={t('Offering type')}
                value={data.offering_type}
                onChange={(value) => {
                  setData('offering_type', value);
                }}
                error={errors.offering_type}
                data={[offeringTypesOptions, missionariesOptions]}
              />

              <CurrencyField
                required
                label={t('Amount')}
                value={data.amount}
                onChange={(value) => {
                  setData('amount', value);
                }}
                error={errors.amount}
              />
            </FieldsGrid>
            <InputField
              label={t('Note')}
              value={data.note}
              onChange={(value) => {
                setData('note', value);
              }}
              error={errors.note}
            />
          </div>
        </Form>
      </div>
    </AppLayout>
  );
}
