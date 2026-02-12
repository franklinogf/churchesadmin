import { Form, Head } from '@inertiajs/react';

import NewPasswordController from '@/actions/App/Http/Controllers/Auth/NewPasswordController';
import { InputField } from '@/components/forms/inputs/InputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { FieldGroup } from '@/components/ui/field';
import { useTranslations } from '@/hooks/use-translations';
import AuthLayout from '@/layouts/auth-layout';

interface ResetPasswordProps {
  token: string;
  email: string;
}

export default function ResetPassword({ token, email }: ResetPasswordProps) {
  const { t } = useTranslations();

  return (
    <AuthLayout title={t('Reset password')} description={t('Please enter your new password below')}>
      <Head title={t('Reset password')} />
      <Form disableWhileProcessing action={NewPasswordController.store()}>
        {({ processing, errors }) => (
          <FieldGroup>
            <input type="hidden" name="token" value={token} />
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
              defaultValue={email}
            />
            <InputField
              label={t('Password')}
              type="password"
              name="password"
              required
              tabIndex={2}
              autoComplete="new-password"
              placeholder={t('Password')}
              error={errors.password}
            />
            <InputField
              label={t('Confirm password')}
              type="password"
              name="password_confirmation"
              autoComplete="new-password"
              placeholder={t('Confirm password')}
              error={errors.password_confirmation}
            />

            <SubmitButton className="w-full" tabIndex={4} isSubmitting={processing}>
              {t('Reset password')}
            </SubmitButton>
          </FieldGroup>
        )}
      </Form>
    </AuthLayout>
  );
}
