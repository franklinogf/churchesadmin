import OfferingTypeController from '@/actions/App/Http/Controllers/OfferingTypeController';
import { InputField } from '@/components/forms/inputs/InputField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import { useTranslations } from '@/hooks/use-translations';
import { type OfferingType } from '@/types/models/offering-type';
import { Form } from '@inertiajs/react';
import { FieldGroup } from '../ui/field';

export function OfferingTypeForm({ offeringType, open, setOpen }: { offeringType?: OfferingType; open: boolean; setOpen: (open: boolean) => void }) {
  const { t } = useTranslations();

  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={offeringType ? t('Edit :model', { model: t('Offering type') }) : t('Add :model', { model: t('Offering type') })}
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
            <InputField required label={t('Name')} name="name" error={errors.name} />

            <ResponsiveModalFooterSubmit isSubmitting={processing} label={t('Save')} />
          </FieldGroup>
        )}
      </Form>
    </ResponsiveModal>
  );
}
