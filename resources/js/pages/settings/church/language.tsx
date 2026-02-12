import { type BreadcrumbItem, type SelectOption } from '@/types';
import { Transition } from '@headlessui/react';
import { router, useForm } from '@inertiajs/react';
import { useMemo, type SubmitEvent } from 'react';

import TenantLanguageController from '@/actions/App/Http/Controllers/Settings/TenantLanguageController';
import { SelectField } from '@/components/forms/inputs/SelectField';
import HeadingSmall from '@/components/heading-small';
import { Button } from '@/components/ui/button';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/church-layout';

type LanguageForm = {
  locale: string;
};

export default function Language({ languages }: { languages: SelectOption[] }) {
  const { t, setLocale, currentLocale } = useTranslations();

  const { data, setData, submit, errors, processing, recentlySuccessful } = useForm<Required<LanguageForm>>({
    locale: currentLocale(),
  });

  const handleSubmit = (e: SubmitEvent<HTMLFormElement>) => {
    e.preventDefault();

    submit(TenantLanguageController.update(), {
      preserveScroll: true,
      onSuccess: () => {
        setLocale(data.locale);
        router.flushAll();
      },
    });
  };

  const breadcrumbs: BreadcrumbItem[] = useMemo(() => [{ title: t('Church Settings') }, { title: t('Church language') }], [t]);

  return (
    <AppLayout title={t('Church Settings')} breadcrumbs={breadcrumbs}>
      <SettingsLayout>
        <div className="space-y-6">
          <HeadingSmall title={t('Language')} description={t('Update the website language')} />

          <form onSubmit={handleSubmit} className="space-y-6">
            <SelectField
              className="max-w-xs"
              options={languages}
              label={t('Language')}
              value={data.locale}
              onValueChange={(value) => setData('locale', value)}
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
