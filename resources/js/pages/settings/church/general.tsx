import { type BreadcrumbItem } from '@/types';
import { Transition } from '@headlessui/react';
import { useForm } from '@inertiajs/react';
import { useMemo, type FormEventHandler } from 'react';

import { InputField } from '@/components/forms/inputs/InputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import HeadingSmall from '@/components/heading-small';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/church-layout';
import type { Church } from '@/types/models/church';
import { useLaravelReactI18n } from 'laravel-react-i18n';

type GeneralForm = {
  name: string;
};

export default function Language({ church }: { church: Church }) {
  const { t } = useLaravelReactI18n();
  const { data, setData, put, errors, processing, recentlySuccessful } = useForm<Required<GeneralForm>>({
    name: church.name,
  });

  const submit: FormEventHandler = (e) => {
    e.preventDefault();

    put(route('church.general.update'), {
      preserveScroll: true,
    });
  };

  const breadcrumbs: BreadcrumbItem[] = useMemo(() => [{ title: t('Church language'), href: route('church.language.edit') }], [t]);

  return (
    <AppLayout title="Church Settings" breadcrumbs={breadcrumbs}>
      <SettingsLayout>
        <div className="space-y-6">
          <HeadingSmall title={t('General information')} description={t('Update the church information')} />

          <form onSubmit={submit} className="space-y-6">
            <InputField
              className="max-w-xs"
              label={t('Church Name')}
              value={data.name}
              onChange={(value) => setData('name', value)}
              error={errors.name}
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
