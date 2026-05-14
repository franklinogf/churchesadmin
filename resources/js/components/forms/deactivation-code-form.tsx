import DeactivationCodeController from '@/actions/App/Http/Controllers/DeactivationCodeController';
import { InputField } from '@/components/forms/inputs/InputField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import { FieldGroup } from '@/components/ui/field';
import { type DeactivationCode } from '@/types/models/deactivation-code';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

export function DeactivationCodeForm({
  deactivationCode,
  open,
  setOpen,
}: {
  deactivationCode?: DeactivationCode;
  open: boolean;
  setOpen: (open: boolean) => void;
}) {
  const { t: tCommon } = useTranslation('common');
  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={
        deactivationCode
          ? tCommon(($) => $.components.forms.deactivationCodeForm.editModel, {
              model: tCommon(($) => $.components.forms.deactivationCodeForm.deactivationCode),
            })
          : tCommon(($) => $.components.forms.deactivationCodeForm.addModel, {
              model: tCommon(($) => $.components.forms.deactivationCodeForm.deactivationCode),
            })
      }
    >
      <Form
        disableWhileProcessing
        action={deactivationCode ? DeactivationCodeController.update(deactivationCode.id) : DeactivationCodeController.store()}
        onSuccess={() => {
          setOpen(false);
        }}
      >
        {({ errors, processing }) => (
          <FieldGroup>
            <InputField
              required
              label={tCommon(($) => $.components.forms.deactivationCodeForm.name)}
              defaultValue={deactivationCode?.name}
              name="name"
              error={errors.name}
            />
            <ResponsiveModalFooterSubmit isSubmitting={processing} label={tCommon(($) => $.components.forms.deactivationCodeForm.save)} />
          </FieldGroup>
        )}
      </Form>
    </ResponsiveModal>
  );
}
