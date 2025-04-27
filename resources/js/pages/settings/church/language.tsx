import { type BreadcrumbItem, type SelectOption, type SharedData } from '@/types';
import { Transition } from '@headlessui/react';
import { useForm, usePage } from '@inertiajs/react';
import { useMemo, type FormEventHandler } from 'react';

import { SelectField } from '@/components/forms/inputs/SelectField';
import HeadingSmall from '@/components/heading-small';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/church-layout';
import { useLaravelReactI18n } from 'laravel-react-i18n';

type LanguageForm = {
  locale: string;
};

export default function Language({ languages }: { languages: SelectOption[] }) {
  const { t, setLocale } = useLaravelReactI18n();
  const {
    props: { locale },
  } = usePage<SharedData>();

  const { data, setData, patch, errors, processing, recentlySuccessful } = useForm<Required<LanguageForm>>({
    locale,
  });

  const submit: FormEventHandler = (e) => {
    e.preventDefault();

    patch(route('church.language.update'), {
      preserveScroll: true,
      onSuccess: () => {
        setLocale(data.locale);
      },
    });
  };

  const breadcrumbs: BreadcrumbItem[] = useMemo(() => [{ title: t('Church language'), href: route('church.language.edit') }], [t]);

  return (
    <AppLayout title="Church Settings" breadcrumbs={breadcrumbs}>
      <SettingsLayout>
        <div className="space-y-6">
          <HeadingSmall title={t('Language')} description={t('Update the website language')} />

          <form onSubmit={submit} className="space-y-6">
            <SelectField
              className="max-w-xs"
              options={languages}
              label={t('Language')}
              value={data.locale}
              onChange={(value) => setData('locale', value)}
              error={errors.locale}
            />
            <div className="flex items-center gap-4">
              <Button disabled={processing}>{t('Save')}</Button>

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
