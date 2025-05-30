import { useTranslations } from '@/hooks/use-translations';
import { type AddressFormData } from '@/types/models/address';
import { CountryField } from './inputs/CountryField';
import { FieldsGrid } from './inputs/FieldsGrid';
import { InputField } from './inputs/InputField';

interface AddressFormSkeletonProps {
  data: AddressFormData;
  setData: (value: AddressFormData) => void;
  errors: Record<string, string>;
  withTitle?: boolean;
  errorsName?: string;
  required?: boolean;
}

export function AddressFormSkeleton({ data, setData, errors, errorsName = 'address', required, withTitle = true }: AddressFormSkeletonProps) {
  const { t } = useTranslations();
  return (
    <section>
      {withTitle && <h2 className="mb-4 text-lg font-semibold">{t('Address')}</h2>}
      <div className="space-y-2">
        <InputField
          required={required}
          label={t('Address line 1')}
          value={data.address_1}
          onChange={(value) => setData({ ...data, address_1: value })}
          error={errors[`${errorsName ? errorsName + '.' : ''}address_1`]}
        />
        <InputField
          required={required}
          label={t('Address line 2')}
          value={data.address_2}
          onChange={(value) => setData({ ...data, address_2: value })}
          error={errors[`${errorsName ? errorsName + '.' : ''}address_2`]}
        />
        <CountryField
          label={t('Country')}
          value={data.country}
          onChange={(country) => setData({ ...data, country })}
          error={errors[`${errorsName ? errorsName + '.' : ''}country`]}
        />
        <FieldsGrid cols={3}>
          <InputField
            required={required}
            label={t('City')}
            value={data.city}
            onChange={(value) => setData({ ...data, city: value })}
            error={errors[`${errorsName ? errorsName + '.' : ''}city`]}
          />
          <InputField
            required={required}
            label={t('State')}
            value={data.state}
            onChange={(value) => setData({ ...data, state: value })}
            error={errors[`${errorsName ? errorsName + '.' : ''}state`]}
          />
          <InputField
            required={required}
            label={t('Zip Code')}
            value={data.zip_code}
            onChange={(value) => setData({ ...data, zip_code: value })}
            error={errors[`${errorsName ? errorsName + '.' : ''}zip_code`]}
          />
        </FieldsGrid>
      </div>
    </section>
  );
}
