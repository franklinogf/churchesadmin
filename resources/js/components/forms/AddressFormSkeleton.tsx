import { type AddressFormData } from '@/types/models/address';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { CountryField } from './inputs/CountryField';
import { FieldsGrid } from './inputs/FieldsGrid';
import { InputField } from './inputs/InputField';

interface AddressFormSkeletonProps {
  data: AddressFormData;
  setData: (value: AddressFormData) => void;
  errors: Record<string, string>;
  withTitle?: boolean;
  errorsName?: string;
}

export function AddressFormSkeleton({ data, setData, errors, errorsName, withTitle = true }: AddressFormSkeletonProps) {
  const { t } = useLaravelReactI18n();
  return (
    <section>
      {withTitle && <h2 className="mb-4 text-lg font-semibold">{t('Address')}</h2>}
      <div className="space-y-2">
        <InputField
          label="Address line 1"
          value={data.address_1}
          onChange={(value) => setData({ ...data, address_1: value })}
          error={errors[`${errorsName ? errorsName + '.' : ''}address_1`]}
        />
        <InputField
          label="Address line 2"
          value={data.address_2}
          onChange={(value) => setData({ ...data, address_2: value })}
          error={errors[`${errorsName ? errorsName + '.' : ''}address_2`]}
        />
        <CountryField
          label="Country"
          value={data.country}
          onChange={(country) => setData({ ...data, country })}
          error={errors[`${errorsName ? errorsName + '.' : ''}country`]}
        />
        <FieldsGrid cols={3}>
          <InputField
            label="City"
            value={data.city}
            onChange={(value) => setData({ ...data, city: value })}
            error={errors[`${errorsName ? errorsName + '.' : ''}city`]}
          />
          <InputField
            label="State"
            value={data.state}
            onChange={(value) => setData({ ...data, state: value })}
            error={errors[`${errorsName ? errorsName + '.' : ''}state`]}
          />
          <InputField
            label="Zip Code"
            value={data.zip_code}
            onChange={(value) => setData({ ...data, zip_code: value })}
            error={errors[`${errorsName ? errorsName + '.' : ''}zip_code`]}
          />
        </FieldsGrid>
      </div>
    </section>
  );
}
