import { type BreadcrumbItem } from '@/types';
import { Transition } from '@headlessui/react';
import { useForm } from '@inertiajs/react';
import { useMemo, type FormEventHandler } from 'react';

import { FileField } from '@/components/forms/inputs/FileField';
import { InputField } from '@/components/forms/inputs/InputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import HeadingSmall from '@/components/heading-small';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/church-layout';
import type { Church } from '@/types/models/church';

type GeneralForm = {
  name: string;
  logo: File | null;
};

export default function Language({ church }: { church: Church }) {
  const { t } = useTranslations();
  const generalForm = useForm<Required<GeneralForm>>({
    name: church.name,
    logo: null,
  });

  const submitGeneral: FormEventHandler = (e) => {
    e.preventDefault();

    generalForm.post(route('church.general.update'), {
      preserveScroll: true,
      onSuccess: () => {
        generalForm.reset();
      },
    });
  };

  const breadcrumbs: BreadcrumbItem[] = useMemo(() => [{ title: t('Church language'), href: route('church.language.edit') }], [t]);

  return (
    <AppLayout title="Church Settings" breadcrumbs={breadcrumbs}>
      <SettingsLayout>
        <div className="space-y-6">
          <HeadingSmall title={t('General information')} description={t('Update the church information')} />

          <form onSubmit={submitGeneral} className="space-y-6">
            <InputField
              required
              className="max-w-xs"
              label={t('Church Name')}
              value={generalForm.data.name}
              onChange={(value) => generalForm.setData('name', value)}
              error={generalForm.errors.name}
            />

            <FileField
              allowImagePreview
              layout="compact"
              initialFileUrls={church.logo}
              label="Logo"
              className="max-w-xs"
              labelIdle="Drop your logo here"
              acceptedFiles={['images']}
              maxFileSize="2MB"
              onChange={(files) => generalForm.setData('logo', files[0] ?? null)}
              error={generalForm.errors.logo}
            />

            <div className="flex items-center gap-4">
              <SubmitButton isSubmitting={generalForm.processing}>{t('Save')}</SubmitButton>

              <Transition
                show={generalForm.recentlySuccessful}
                enter="transition ease-in-out"
                enterFrom="opacity-0"
                leave="transition ease-in-out"
                leaveTo="opacity-0"
              >
                <p className="text-sm text-neutral-600">{t('Saved')}</p>
              </Transition>
            </div>
          </form>
        </div>
      </SettingsLayout>
    </AppLayout>
  );
}
