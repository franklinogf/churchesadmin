import DeactivationCodeController from '@/actions/App/Http/Controllers/DeactivationCodeController';
import { InputField } from '@/components/forms/inputs/InputField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import { FieldGroup } from '@/components/ui/field';
import { useTranslations } from '@/hooks/use-translations';
import { type DeactivationCode } from '@/types/models/deactivation-code';
import { Form } from '@inertiajs/react';

export function DeactivationCodeForm({
  deactivationCode,
  open,
  setOpen,
}: {
  deactivationCode?: DeactivationCode;
  open: boolean;
  setOpen: (open: boolean) => void;
}) {
  const { t } = useTranslations();

  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={deactivationCode ? t('Edit :model', { model: t('Deactivation code') }) : t('Add :model', { model: t('Deactivation code') })}
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
            <InputField required label={t('Name')} defaultValue={deactivationCode?.name} name="name" error={errors.name} />
            <ResponsiveModalFooterSubmit isSubmitting={processing} label={t('Save')} />
          </FieldGroup>
        )}
      </Form>
    </ResponsiveModal>
  );
}
