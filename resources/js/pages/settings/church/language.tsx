import { type BreadcrumbItem, type SelectOption } from '@/types';
import { Transition } from '@headlessui/react';
import { router, useForm } from '@inertiajs/react';
import { useMemo, type SubmitEvent } from 'react';

import TenantLanguageController from '@/actions/App/Http/Controllers/Settings/TenantLanguageController';
import { SelectField } from '@/components/forms/inputs/SelectField';
import HeadingSmall from '@/components/heading-small';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/church-layout';
import { useTranslation } from 'react-i18next';

type LanguageForm = {
  locale: string;
};

export default function Language({ languages }: { languages: SelectOption[] }) {
  const { i18n } = useTranslation();

  const { t: tPages } = useTranslation('pages');
  const { data, setData, submit, errors, processing, recentlySuccessful } = useForm<Required<LanguageForm>>({
    locale: i18n.language,
  });

  const handleSubmit = (e: SubmitEvent<HTMLFormElement>) => {
    e.preventDefault();

    submit(TenantLanguageController.update(), {
      preserveScroll: true,
      onSuccess: () => {
        void i18n.changeLanguage(data.locale);
        router.flushAll();
      },
    });
  };

  const breadcrumbs: BreadcrumbItem[] = useMemo(
    () => [{ title: tPages(($) => $.settings.church.language.churchSettings) }, { title: tPages(($) => $.settings.church.language.churchLanguage) }],
    [tPages],
  );

  return (
    <AppLayout title={tPages(($) => $.settings.church.language.churchSettings)} breadcrumbs={breadcrumbs}>
      <SettingsLayout>
        <div className="space-y-6">
          <HeadingSmall
            title={tPages(($) => $.settings.church.language.language)}
            description={tPages(($) => $.settings.church.language.updateTheWebsiteLanguage)}
          />

          <form onSubmit={handleSubmit} className="space-y-6">
            <SelectField
              className="max-w-xs"
              options={languages}
              label={tPages(($) => $.settings.church.language.language)}
              value={data.locale}
              onValueChange={(value) => setData('locale', value)}
              error={errors.locale}
            />
            <div className="flex items-center gap-4">
              <Button disabled={processing}>{tPages(($) => $.settings.church.language.save)}</Button>

              <Transition
                show={recentlySuccessful}
                enter="transition ease-in-out"
                enterFrom="opacity-0"
                leave="transition ease-in-out"
                leaveTo="opacity-0"
              >
                <p className="text-sm text-neutral-600">{tPages(($) => $.settings.church.language.saved)}</p>
              </Transition>
            </div>
          </form>
        </div>
      </SettingsLayout>
    </AppLayout>
  );
}
