import { InputField } from '@/components/forms/inputs/InputField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import { useTranslations } from '@/hooks/use-translations';
import { type DeactivationCode } from '@/types/models/deactivation-code';
import { useForm } from '@inertiajs/react';

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
  const { data, setData, post, put, errors, processing, reset } = useForm({
    name: deactivationCode?.name ?? '',
  });

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    if (deactivationCode) {
      put(route('codes.deactivationCodes.update', deactivationCode.id), {
        onSuccess: () => {
          setOpen(false);
        },
      });
    } else {
      post(route('codes.deactivationCodes.store'), {
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
      title={deactivationCode ? t('Edit :model', { model: t('Deactivation code') }) : t('Add :model', { model: t('Deactivation code') })}
    >
      <form className="space-y-4" onSubmit={handleSubmit}>
        <InputField required label={t('Name')} value={data.name} onChange={(value) => setData('name', value)} error={errors.name} />

        <ResponsiveModalFooterSubmit isSubmitting={processing} label={t('Save')} />
      </form>
    </ResponsiveModal>
  );
}
