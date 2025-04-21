import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { TranslatableInput } from '@/components/forms/inputs/TranslatableInputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { useTranslations } from '@/hooks/use-empty-translations';
import AppLayout from '@/layouts/app-layout';
import { OfferingType } from '@/types/models/offering-types';
import { useForm } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { useState } from 'react';
import { columns } from './includes/columns';

export default function OfferingTypesIndex({ offeringTypes }: { offeringTypes: OfferingType[] }) {
  const { t } = useLaravelReactI18n();
  return (
    <AppLayout title={t('Offering Types')} breadcrumbs={[{ title: t('Offering Types') }]}>
      <PageTitle>{t('Offering Types')}</PageTitle>
      <DataTable
        headerButton={
          <OfferingTypeForm>
            <Button>{t('Add Offering Type')}</Button>
          </OfferingTypeForm>
        }
        data={offeringTypes}
        columns={columns}
      />
    </AppLayout>
  );
}

export function OfferingTypeForm({ offeringType, children }: { offeringType?: OfferingType; children: React.ReactNode }) {
  const [open, setOpen] = useState(false);
  const { t } = useLaravelReactI18n();
  const { emptyTranslations } = useTranslations();
  const { data, setData, post, put, errors, reset, processing } = useForm({
    name: offeringType?.nameTranslations ?? emptyTranslations,
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
          reset();
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
          <TranslatableInput
            required
            label={t('Name')}
            values={data.name}
            onChange={(locale, value) => setData(`name`, { ...data.name, [locale]: value })}
            errors={{ errors, name: 'name' }}
          />

          <div className="flex justify-end">
            <SubmitButton isSubmitting={processing}>{t('Save')}</SubmitButton>
          </div>
        </form>
      </DialogContent>
    </Dialog>
  );
}
