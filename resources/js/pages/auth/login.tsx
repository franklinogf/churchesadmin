import { Form, Head, Link, usePage } from '@inertiajs/react';

import TextLink from '@/components/text-link';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth-layout';

import AuthenticatedSessionController from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import PasswordResetLinkController from '@/actions/App/Http/Controllers/Auth/PasswordResetLinkController';
import LoginLinkController from '@/actions/App/Http/Controllers/LoginLinkController';
import { InputField } from '@/components/forms/inputs/InputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { FieldGroup } from '@/components/ui/field';
import { TenantRole } from '@/enums/TenantRole';
import { useTranslations } from '@/hooks/use-translations';
import type { SharedData } from '@/types';

export default function Login() {
  const { environment } = usePage<SharedData>().props;
  const { t } = useTranslations();

  return (
    <AuthLayout title={t('Log in to your account')} description={t('Enter your email and password below to log in')}>
      <Head title={t('Log in')} />
      {environment !== 'production' && (
        <div className="mb-6 flex flex-col gap-2">
          <Link method="post" href={LoginLinkController()} data={{ role: TenantRole.SUPER_ADMIN }}>
            Log in as super admin
          </Link>
          <Link method="post" href={LoginLinkController()} data={{ role: TenantRole.ADMIN }}>
            Log in as admin
          </Link>
          <Link method="post" href={LoginLinkController()} data={{ role: TenantRole.SECRETARY }}>
            Log in as secretary
          </Link>
        </div>
      )}
      <Form
        resetOnError={['password']}
        disableWhileProcessing
        transform={(data) => ({
          ...data,
          remember: data.remember === 'on' ? true : false,
        })}
        action={AuthenticatedSessionController.store()}
      >
        {({ processing, errors }) => (
          <FieldGroup>
            <InputField
              label={t('Email')}
              type="email"
              name="email"
              required
              autoFocus
              tabIndex={1}
              autoComplete="email"
              placeholder={t('email@example.com')}
              error={errors.email}
            />

            <div className="grid gap-2">
              <InputField
                label={t('Password')}
                type="password"
                name="password"
                required
                tabIndex={2}
                autoComplete="current-password"
                placeholder={t('Password')}
                error={errors.password}
              />
              <TextLink href={PasswordResetLinkController.create()} className="ml-auto text-sm" tabIndex={5}>
                {t('Forgot your password?')}
              </TextLink>
            </div>

            <div className="flex items-center space-x-3">
              <Checkbox id="remember" name="remember" tabIndex={3} />
              <Label htmlFor="remember">{t('Remember me')}</Label>
            </div>

            <SubmitButton className="w-full" tabIndex={4} isSubmitting={processing}>
              {t('Log in')}
            </SubmitButton>
          </FieldGroup>
        )}
      </Form>
    </AuthLayout>
  );
}
