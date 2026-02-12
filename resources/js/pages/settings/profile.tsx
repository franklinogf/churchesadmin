import { type BreadcrumbItem, type SelectOption, type SharedData } from '@/types';
import { Transition } from '@headlessui/react';
import { Link, router, useForm, usePage } from '@inertiajs/react';
import { useMemo, type SubmitEventHandler } from 'react';

import EmailVerificationNotificationController from '@/actions/App/Http/Controllers/Auth/EmailVerificationNotificationController';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import { ComboboxField } from '@/components/forms/inputs/ComboboxField';
import { CountryField } from '@/components/forms/inputs/CountryField';
import { InputField } from '@/components/forms/inputs/InputField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import HeadingSmall from '@/components/heading-small';
import { Button } from '@/components/ui/button';
import { TenantRole } from '@/enums/TenantRole';
import { useTranslations } from '@/hooks/use-translations';
import { useUser } from '@/hooks/use-user';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/profile-layout';

type ProfileForm = {
  name: string;
  email: string;
  timezone: string;
  timezone_country: string;
  current_year_id: string;
};
interface ProfileProps {
  mustVerifyEmail: boolean;
  status?: string;
  timezones: SelectOption[];
  country: string;
  workingYears: SelectOption[];
}
export default function Profile({ mustVerifyEmail, status, timezones, country, workingYears }: ProfileProps) {
  const { t } = useTranslations();
  const { hasRole } = useUser();
  const { auth } = usePage<SharedData>().props;

  const { data, setData, submit, errors, processing, recentlySuccessful } = useForm<Required<ProfileForm>>({
    name: auth.user.name,
    email: auth.user.email,
    timezone: country === auth.user.timezoneCountry ? auth.user.timezone : '',
    timezone_country: country,
    current_year_id: auth.user.currentYearId.toString(),
  });
  const handleSubmit: SubmitEventHandler<HTMLFormElement> = (e) => {
    e.preventDefault();

    submit(ProfileController.update(), {
      preserveScroll: true,
    });
  };
  const breadcrumbs: BreadcrumbItem[] = useMemo(
    () => [
      {
        title: t('Profile Settings'),
        href: ProfileController.edit().url,
      },
    ],
    [t],
  );
  return (
    <AppLayout title={t('Profile Settings')} breadcrumbs={breadcrumbs}>
      <SettingsLayout>
        <div className="space-y-6">
          <HeadingSmall title={t('Profile information')} description={t('Update your name and email address')} />

          <form onSubmit={handleSubmit} className="space-y-6">
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
            {mustVerifyEmail && auth.user.emailVerifiedAt === null && (
              <div>
                <p className="text-muted-foreground -mt-4 text-sm">
                  {t('Your email address is unverified.')}{' '}
                  <Link
                    href={EmailVerificationNotificationController.store()}
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

            <CountryField
              label={t('Country')}
              value={data.timezone_country}
              onChange={(country) => {
                router.visit(ProfileController.edit({ query: { country } }), { preserveScroll: true, only: ['timezones', 'country'] });
                setData('timezone_country', country);
                setData('timezone', ''); // Reset timezone when country changes
              }}
              error={errors.timezone_country}
              required
            />
            <ComboboxField
              label={t('Timezone')}
              value={data.timezone}
              onChange={(value) => setData('timezone', value)}
              required
              error={errors.timezone}
              options={timezones}
            />

            {hasRole(TenantRole.SUPER_ADMIN) && (
              <div>
                <SelectField
                  label={t('Current year')}
                  value={data.current_year_id}
                  onValueChange={(value) => setData('current_year_id', value)}
                  required
                  error={errors.current_year_id}
                  options={workingYears}
                />
                <small className="text-muted-foreground text-sm">
                  {t('This setting is only available for Super Admins.')}
                  <br />
                  {t('It allows you to set the current year for the application for you to work in.')}
                </small>
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
