import OfferingTypeController from '@/actions/App/Http/Controllers/OfferingTypeController';
import { InputField } from '@/components/forms/inputs/InputField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import { type OfferingType } from '@/types/models/offering-type';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { FieldGroup } from '../ui/field';

export function OfferingTypeForm({ offeringType, open, setOpen }: { offeringType?: OfferingType; open: boolean; setOpen: (open: boolean) => void }) {
  const { t: tCommon } = useTranslation('common');
  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={
        offeringType
          ? tCommon(($) => $.components.forms.offeringTypeForm.editModel, { model: tCommon(($) => $.components.forms.offeringTypeForm.offeringType) })
          : tCommon(($) => $.components.forms.offeringTypeForm.addModel, { model: tCommon(($) => $.components.forms.offeringTypeForm.offeringType) })
      }
    >
      <Form
        disableWhileProcessing
        action={offeringType ? OfferingTypeController.update(offeringType) : OfferingTypeController.store()}
        onSuccess={() => {
          setOpen(false);
        }}
      >
        {({ errors, processing }) => (
          <FieldGroup>
            <InputField required label={tCommon(($) => $.components.forms.offeringTypeForm.name)} name="name" error={errors.name} />

            <ResponsiveModalFooterSubmit isSubmitting={processing} label={tCommon(($) => $.components.forms.offeringTypeForm.save)} />
          </FieldGroup>
        )}
      </Form>
    </ResponsiveModal>
  );
}
