import { type BreadcrumbItem, type SelectOption, type SharedData } from '@/types';
import { Transition } from '@headlessui/react';
import { Link, router, useForm, usePage } from '@inertiajs/react';
import { useMemo, type FormEventHandler } from 'react';

import { ComboboxField } from '@/components/forms/inputs/ComboboxField';
import { CountryField } from '@/components/forms/inputs/CountryField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import HeadingSmall from '@/components/heading-small';
import { Button } from '@/components/ui/button';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/profile-layout';

type ProfileForm = {
  name: string;
  email: string;
  timezone: string;
  timezone_country: string;
};
interface ProfileProps {
  mustVerifyEmail: boolean;
  status?: string;
  timezones: SelectOption[];
  country: string;
}
export default function Profile({ mustVerifyEmail, status, timezones, country }: ProfileProps) {
  const { t } = useTranslations();
  const { auth } = usePage<SharedData>().props;

  const { data, setData, patch, errors, processing, recentlySuccessful } = useForm<Required<ProfileForm>>({
    name: auth.user.name,
    email: auth.user.email,
    timezone: auth.user.timezone ?? 'America/Puerto_Rico',
    timezone_country: country,
  });

  const submit: FormEventHandler = (e) => {
    e.preventDefault();

    patch(route('profile.update'), {
      preserveScroll: true,
    });
  };
  const breadcrumbs: BreadcrumbItem[] = useMemo(
    () => [
      {
        title: t('Profile Settings'),
        href: route('profile.edit'),
      },
    ],
    [t],
  );

  return (
    <AppLayout title={t('Profile Settings')} breadcrumbs={breadcrumbs}>
      <SettingsLayout>
        <div className="space-y-6">
          <HeadingSmall title={t('Profile information')} description={t('Update your name and email address')} />

          <form onSubmit={submit} className="space-y-6">
            <InputField
              label={t('Name')}
              value={data.name}
              onChange={(value) => setData('name', value)}
              required
              autoComplete="name"
              placeholder={t('Full name')}
              error={errors.name}
            />

            <InputField
              label={t('Email address')}
              value={data.email}
              onChange={(value) => setData('email', value)}
              required
              autoComplete="username"
              placeholder={t('Email address')}
              error={errors.email}
            />

            <FieldsGrid>
              <CountryField
                label={t('Country')}
                value={data.timezone_country}
                onChange={(country) => {
                  router.get(route('profile.edit'), { country }, { preserveScroll: true, only: ['timezones', 'country'] });
                  setData('timezone_country', country);
                }}
                error={errors.timezone_country}
              />
              <ComboboxField
                label={t('Timezone')}
                value={data.timezone}
                onChange={(value) => setData('timezone', value)}
                required
                error={errors.timezone}
                options={timezones}
              />
            </FieldsGrid>

            {mustVerifyEmail && auth.user.emailVerifiedAt === null && (
              <div>
                <p className="text-muted-foreground -mt-4 text-sm">
                  {t('Your email address is unverified.')}
                  <Link
                    href={route('verification.send')}
                    method="post"
                    as="button"
                    className="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                  >
                    {t('Click here to resend the verification email.')}
                  </Link>
                </p>

                {status === 'verification-link-sent' && (
                  <div className="mt-2 text-sm font-medium text-green-600">{t('A new verification link has been sent to your email address.')}</div>
                )}
              </div>
            )}

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
