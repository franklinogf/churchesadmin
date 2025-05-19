import { InputField } from '@/components/forms/inputs/InputField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import { type OfferingType } from '@/types/models/offering-type';
import { useForm } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';

export function OfferingTypeForm({ offeringType, open, setOpen }: { offeringType?: OfferingType; open: boolean; setOpen: (open: boolean) => void }) {
  const { t } = useLaravelReactI18n();
  const { data, setData, post, put, errors, processing, reset } = useForm({
    name: offeringType?.name ?? '',
  });

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    if (offeringType) {
      put(route('codes.offeringTypes.update', offeringType.id), {
        onSuccess: () => {
          setOpen(false);
        },
      });
    } else {
      post(route('codes.offeringTypes.store'), {
        preserveState: false,
        onSuccess: () => {
          setOpen(false);
          reset();
        },
      });
    }
  }
  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={offeringType ? t('Edit :model', { model: t('Offering type') }) : t('Add :model', { model: t('Offering type') })}
    >
      <form className="space-y-4" onSubmit={handleSubmit}>
        <InputField required label={t('Name')} value={data.name} onChange={(value) => setData('name', value)} error={errors.name} />

        <ResponsiveModalFooterSubmit isSubmitting={processing} label={t('Save')} />
      </form>
    </ResponsiveModal>
  );
}
