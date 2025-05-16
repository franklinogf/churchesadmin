import { Form } from '@/components/forms/Form';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { Input } from '@/components/ui/input';
import { useForm } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';

export function CreateCheckLayoutForm({ walletId }: { walletId?: number }) {
  const { t } = useLaravelReactI18n();
  const { data, setData, processing, post } = useForm('create-check-layout', {
    wallet_id: walletId?.toString() ?? '',
    name: '',
    width: '',
    height: '',
    image: null as File | null,
  });
  function handleCreateLayout() {
    post(route('check-layout.store'));
  }
  return (
    <Form isSubmitting={processing} submitLabel={t('Create layout')} onSubmit={handleCreateLayout}>
      <InputField required label={t('Name')} placeholder={t('Enter layout name')} value={data.name} onChange={(value) => setData('name', value)} />
      <Input required max={1} type="file" accept="image/*" onChange={(e) => setData('image', e.target.files?.[0] || null)} />
      <FieldsGrid>
        <InputField
          required
          label={t('Width')}
          placeholder={t('Enter layout width')}
          value={data.width}
          onChange={(value) => setData('width', value)}
        />
        <InputField
          required
          label={t('Height')}
          placeholder={t('Enter layout height')}
          value={data.height}
          onChange={(value) => setData('height', value)}
        />
      </FieldsGrid>
    </Form>
  );
}
