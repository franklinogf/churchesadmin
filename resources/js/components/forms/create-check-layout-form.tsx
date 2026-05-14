import CheckLayoutController from '@/actions/App/Http/Controllers/CheckLayoutController';
import { Form } from '@/components/forms/Form';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { Input } from '@/components/ui/input';
import { useForm } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

export function CreateCheckLayoutForm({ walletId }: { walletId?: number }) {
  const { t: tCommon } = useTranslation('common');
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
    <Form isSubmitting={processing} submitLabel={tCommon(($) => $.components.forms.createCheckLayoutForm.createLayout)} onSubmit={handleCreateLayout}>
      <InputField
        required
        label={tCommon(($) => $.components.forms.createCheckLayoutForm.name)}
        placeholder={tCommon(($) => $.components.forms.createCheckLayoutForm.enterLayoutName)}
        value={data.name}
        onChange={(value) => setData('name', value)}
      />
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
          label={tCommon(($) => $.components.forms.createCheckLayoutForm.width)}
          placeholder={tCommon(($) => $.components.forms.createCheckLayoutForm.enterLayoutWidth)}
          value={data.width}
          onChange={(value) => setData('width', value)}
        />
        <InputField
          required
          label={tCommon(($) => $.components.forms.createCheckLayoutForm.height)}
          placeholder={tCommon(($) => $.components.forms.createCheckLayoutForm.enterLayoutHeight)}
          value={data.height}
          onChange={(value) => setData('height', value)}
        />
      </FieldsGrid>
    </Form>
  );
}
