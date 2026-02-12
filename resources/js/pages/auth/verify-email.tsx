// Components
import { Form, Head } from '@inertiajs/react';

import AuthenticatedSessionController from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import EmailVerificationNotificationController from '@/actions/App/Http/Controllers/Auth/EmailVerificationNotificationController';
import { SubmitButton } from '@/components/forms/SubmitButton';
import TextLink from '@/components/text-link';
import { FieldGroup } from '@/components/ui/field';
import { useTranslations } from '@/hooks/use-translations';
import AuthLayout from '@/layouts/auth-layout';

export default function VerifyEmail({ status }: { status?: string }) {
  const { t } = useTranslations();
  return (
    <AuthLayout title={t('Verify email')} description={t('Please verify your email address by clicking on the link we just emailed to you.')}>
      <Head title={t('Email verification')} />

      {status === 'verification-link-sent' && (
        <div className="mb-4 text-center text-sm font-medium text-green-600">
          {t('A new verification link has been sent to the email address you provided during registration.')}
        </div>
      )}

      <Form disableWhileProcessing action={EmailVerificationNotificationController.store()}>
        {({ processing }) => (
          <FieldGroup>
            <SubmitButton isSubmitting={processing} variant="secondary">
              {t('Resend verification email')}
            </SubmitButton>

            <TextLink href={AuthenticatedSessionController.destroy()} method="post" className="mx-auto block text-sm">
              {t('Log out')}
            </TextLink>
          </FieldGroup>
        )}
      </Form>
    </AuthLayout>
  );
}
