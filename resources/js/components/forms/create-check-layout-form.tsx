import CheckLayoutController from '@/actions/App/Http/Controllers/CheckLayoutController';
import { Form } from '@/components/forms/Form';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { Input } from '@/components/ui/input';
import { useTranslations } from '@/hooks/use-translations';
import { useForm } from '@inertiajs/react';

export function CreateCheckLayoutForm({ walletId }: { walletId?: number }) {
  const { t } = useTranslations();
  const { data, setData, processing, submit } = useForm('create-check-layout', {
    wallet_id: walletId?.toString() ?? '',
    name: '',
    width: '',
    height: '',
    image: null as File | null,
  });
  function handleCreateLayout() {
    submit(CheckLayoutController.store(), { preserveState: false });
  }
  return (
    <Form isSubmitting={processing} submitLabel={t('Create layout')} onSubmit={handleCreateLayout}>
      <InputField required label={t('Name')} placeholder={t('Enter layout name')} value={data.name} onChange={(value) => setData('name', value)} />
      <Input
        required
        max={1}
        type="file"
        accept="image/*"
        onChange={(e) => {
          const file = e.target.files?.[0] || null;
          if (!file) {
            return;
          }
          const img = new Image();
          img.onload = () => {
            setData('width', img.width.toString());
            setData('height', img.height.toString());
            URL.revokeObjectURL(img.src);
          };
          img.src = URL.createObjectURL(file);

          setData('image', file);
        }}
      />
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
