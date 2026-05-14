// Components
import { Form, Head } from '@inertiajs/react';

import AuthenticatedSessionController from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import EmailVerificationNotificationController from '@/actions/App/Http/Controllers/Auth/EmailVerificationNotificationController';
import { SubmitButton } from '@/components/forms/SubmitButton';
import TextLink from '@/components/text-link';
import { FieldGroup } from '@/components/ui/field';
import AuthLayout from '@/layouts/auth-layout';
import { useTranslation } from 'react-i18next';

export default function VerifyEmail({ status }: { status?: string }) {
  const { t: tPages } = useTranslation('pages');
  return (
    <AuthLayout
      title={tPages(($) => $.auth.verifyEmail.verifyEmail)}
      description={tPages(($) => $.auth.verifyEmail.pleaseVerifyYourEmailAddressByClickingOnThe)}
    >
      <Head title={tPages(($) => $.auth.verifyEmail.emailVerification)} />

      {status === 'verification-link-sent' && (
        <div className="mb-4 text-center text-sm font-medium text-green-600">
          {tPages(($) => $.auth.verifyEmail.aNewVerificationLinkHasBeenSentToThe)}
        </div>
      )}

      <Form disableWhileProcessing action={EmailVerificationNotificationController.store()}>
        {({ processing }) => (
          <FieldGroup>
            <SubmitButton isSubmitting={processing} variant="secondary">
              {tPages(($) => $.auth.verifyEmail.resendVerificationEmail)}
            </SubmitButton>

            <TextLink href={AuthenticatedSessionController.destroy()} method="post" className="mx-auto block text-sm">
              {tPages(($) => $.auth.verifyEmail.logOut)}
            </TextLink>
          </FieldGroup>
        )}
      </Form>
    </AuthLayout>
  );
}
