import { InputField } from '@/components/forms/inputs/InputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { type OfferingType } from '@/types/models/offering-type';
import { useForm } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { useState } from 'react';

export function OfferingTypeForm({ offeringType, children }: { offeringType?: OfferingType; children: React.ReactNode }) {
  const [open, setOpen] = useState(false);
  const { t } = useLaravelReactI18n();
  const { data, setData, post, put, errors, processing } = useForm({
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
        onSuccess: () => {
          setOpen(false);
          setData({ name: '' });
        },
      });
    }
  }
  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger asChild>{children}</DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>{offeringType ? t('Edit Offering Type') : t('Add Offering Type')}</DialogTitle>
          <DialogDescription hidden></DialogDescription>
        </DialogHeader>

        <form className="space-y-4" onSubmit={handleSubmit}>
          <InputField required label={t('Name')} value={data.name} onChange={(value) => setData('name', value)} error={errors.name} />

          <div className="flex justify-end">
            <SubmitButton isSubmitting={processing}>{t('Save')}</SubmitButton>
          </div>
        </form>
      </DialogContent>
    </Dialog>
  );
}
