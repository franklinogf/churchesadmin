import { Form, Head } from '@inertiajs/react';

import AuthenticatedSessionController from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import PasswordResetLinkController from '@/actions/App/Http/Controllers/Auth/PasswordResetLinkController';
import { InputField } from '@/components/forms/inputs/InputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import TextLink from '@/components/text-link';
import { FieldGroup } from '@/components/ui/field';
import { useTranslations } from '@/hooks/use-translations';
import AuthLayout from '@/layouts/auth-layout';

export default function ForgotPassword({ status }: { status?: string }) {
  const { t } = useTranslations();
  return (
    <AuthLayout title={t('Forgot password')} description={t('Enter your email to receive a password reset link')}>
      <Head title={t('Forgot password')} />

      {status && <div className="mb-4 text-center text-sm font-medium text-green-600">{status}</div>}

      <div className="space-y-6">
        <Form disableWhileProcessing action={PasswordResetLinkController.store()}>
          {({ processing, errors }) => (
            <FieldGroup>
              <InputField type="email" name="email" autoComplete="off" autoFocus placeholder={t('email@example.com')} error={errors.email} />

              <SubmitButton isSubmitting={processing}>{t('Email password reset link')}</SubmitButton>
            </FieldGroup>
          )}
        </Form>

        <div className="text-muted-foreground space-x-1 text-center text-sm">
          <span>{t('Or, return to')}</span>
          <TextLink href={AuthenticatedSessionController.create()}>{t('log in')}</TextLink>
        </div>
      </div>
    </AuthLayout>
  );
}
