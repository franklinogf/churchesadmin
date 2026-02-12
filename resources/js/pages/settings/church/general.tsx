import { type BreadcrumbItem } from '@/types';
import { Transition } from '@headlessui/react';
import { useForm } from '@inertiajs/react';
import { useMemo, type SubmitEvent } from 'react';

import TenantGeneralController from '@/actions/App/Http/Controllers/Settings/TenantGeneralController';
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
  const { data, setData, submit, errors, processing, recentlySuccessful } = useForm<Required<GeneralForm>>({
    name: church.name,
    logo: null,
  });

  const handleSubmit = (e: SubmitEvent<HTMLFormElement>) => {
    e.preventDefault();

    submit(TenantGeneralController.update(), {
      preserveScroll: true,
    });
  };

  const breadcrumbs: BreadcrumbItem[] = useMemo(() => [{ title: t('Church Settings') }, { title: t('General information') }], [t]);

  return (
    <AppLayout title="Church Settings" breadcrumbs={breadcrumbs}>
      <SettingsLayout>
        <div className="space-y-6">
          <HeadingSmall title={t('General information')} description={t('Update the church information')} />

          <form onSubmit={handleSubmit} className="space-y-6">
            <InputField
              required
              className="max-w-xs"
              label={t('Church Name')}
              value={data.name}
              onChange={(value) => setData('name', value)}
              error={errors.name}
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
              onChange={(files) => setData('logo', files[0] ?? null)}
              error={errors.logo}
            />

            <div className="flex items-center gap-4">
              <SubmitButton isSubmitting={processing}>{t('Save')}</SubmitButton>

              <Transition
                show={recentlySuccessful}
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
