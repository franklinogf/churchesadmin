import { Form, Head } from '@inertiajs/react';

import AuthenticatedSessionController from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import PasswordResetLinkController from '@/actions/App/Http/Controllers/Auth/PasswordResetLinkController';
import { InputField } from '@/components/forms/inputs/InputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import TextLink from '@/components/text-link';
import { FieldGroup } from '@/components/ui/field';
import AuthLayout from '@/layouts/auth-layout';
import { useTranslation } from 'react-i18next';

export default function ForgotPassword({ status }: { status?: string }) {
  const { t: tPages } = useTranslation('pages');
  return (
    <AuthLayout
      title={tPages(($) => $.auth.forgotPassword.forgotPassword)}
      description={tPages(($) => $.auth.forgotPassword.enterYourEmailToReceiveAPasswordResetLink)}
    >
      <Head title={tPages(($) => $.auth.forgotPassword.forgotPassword)} />

      {status && <div className="mb-4 text-center text-sm font-medium text-green-600">{status}</div>}

      <div className="space-y-6">
        <Form disableWhileProcessing action={PasswordResetLinkController.store()}>
          {({ processing, errors }) => (
            <FieldGroup>
              <InputField
                type="email"
                name="email"
                autoComplete="off"
                autoFocus
                placeholder={tPages(($) => $.auth.forgotPassword.emailExampleCom)}
                error={errors.email}
              />

              <SubmitButton isSubmitting={processing}>{tPages(($) => $.auth.forgotPassword.emailPasswordResetLink)}</SubmitButton>
            </FieldGroup>
          )}
        </Form>

        <div className="text-muted-foreground space-x-1 text-center text-sm">
          <span>{tPages(($) => $.auth.forgotPassword.orReturnTo)}</span>
          <TextLink href={AuthenticatedSessionController.create()}>{tPages(($) => $.auth.forgotPassword.logIn)}</TextLink>
        </div>
      </div>
    </AuthLayout>
  );
}
