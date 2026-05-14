import { Form, Head } from '@inertiajs/react';

import NewPasswordController from '@/actions/App/Http/Controllers/Auth/NewPasswordController';
import { InputField } from '@/components/forms/inputs/InputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { FieldGroup } from '@/components/ui/field';
import AuthLayout from '@/layouts/auth-layout';
import { useTranslation } from 'react-i18next';

interface ResetPasswordProps {
  token: string;
  email: string;
}

export default function ResetPassword({ token, email }: ResetPasswordProps) {
  const { t: tPages } = useTranslation('pages');
  return (
    <AuthLayout
      title={tPages(($) => $.auth.resetPassword.resetPassword)}
      description={tPages(($) => $.auth.resetPassword.pleaseEnterYourNewPasswordBelow)}
    >
      <Head title={tPages(($) => $.auth.resetPassword.resetPassword)} />
      <Form disableWhileProcessing action={NewPasswordController.store()}>
        {({ processing, errors }) => (
          <FieldGroup>
            <input type="hidden" name="token" value={token} />
            <InputField
              label={tPages(($) => $.auth.resetPassword.email)}
              type="email"
              name="email"
              required
              autoFocus
              tabIndex={1}
              autoComplete="email"
              placeholder={tPages(($) => $.auth.resetPassword.emailExampleCom)}
              error={errors.email}
              defaultValue={email}
            />
            <InputField
              label={tPages(($) => $.auth.resetPassword.password)}
              type="password"
              name="password"
              required
              tabIndex={2}
              autoComplete="new-password"
              placeholder={tPages(($) => $.auth.resetPassword.password)}
              error={errors.password}
            />
            <InputField
              label={tPages(($) => $.auth.resetPassword.confirmPassword)}
              type="password"
              name="password_confirmation"
              autoComplete="new-password"
              placeholder={tPages(($) => $.auth.resetPassword.confirmPassword)}
              error={errors.password_confirmation}
            />

            <SubmitButton className="w-full" tabIndex={4} isSubmitting={processing}>
              {tPages(($) => $.auth.resetPassword.resetPassword)}
            </SubmitButton>
          </FieldGroup>
        )}
      </Form>
    </AuthLayout>
  );
}
