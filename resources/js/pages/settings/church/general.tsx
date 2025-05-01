import { type BreadcrumbItem } from '@/types';
import { Transition } from '@headlessui/react';
import { useForm } from '@inertiajs/react';
import { useMemo, type FormEventHandler } from 'react';

import { InputField } from '@/components/forms/inputs/InputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import HeadingSmall from '@/components/heading-small';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/church-layout';
import type { Church } from '@/types/models/church';
import { useLaravelReactI18n } from 'laravel-react-i18n';

type GeneralForm = {
  name: string;
};

export default function Language({ church }: { church: Church }) {
  const { t } = useLaravelReactI18n();
  const generalForm = useForm<Required<GeneralForm>>('general', {
    name: church.name,
  });
  const logoForm = useForm('logo', {
    logo: null as File | null,
  });

  const submitGeneral: FormEventHandler = (e) => {
    e.preventDefault();

    generalForm.put(route('church.general.update'), {
      preserveScroll: true,
    });
  };

  const submitLogo: FormEventHandler = (e) => {
    e.preventDefault();

    logoForm.post(route('church.logo'), {
      preserveScroll: true,
      onSuccess: () => {
        logoForm.setData('logo', null);
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
              className="max-w-xs"
              label={t('Church Name')}
              value={generalForm.data.name}
              onChange={(value) => generalForm.setData('name', value)}
              error={generalForm.errors.name}
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
          <HeadingSmall title={t('Logo')} description={t('Upload the church logo')} />
          <form onSubmit={submitLogo} className="space-y-6">
            <Input type="file" accept="image/*" onChange={(e) => logoForm.setData('logo', e.target.files?.[0] || null)} />
            <div className="flex items-center gap-4">
              <SubmitButton isSubmitting={logoForm.processing}>{t('Save')}</SubmitButton>

              <Transition
                show={logoForm.recentlySuccessful}
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
