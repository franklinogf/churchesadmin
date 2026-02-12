// Components
import { Form, Head } from '@inertiajs/react';

import ConfirmablePasswordController from '@/actions/App/Http/Controllers/Auth/ConfirmablePasswordController';
import { InputField } from '@/components/forms/inputs/InputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { FieldGroup } from '@/components/ui/field';
import { useTranslations } from '@/hooks/use-translations';
import AuthLayout from '@/layouts/auth-layout';

export default function ConfirmPassword() {
  const { t } = useTranslations();

  return (
    <AuthLayout title="Confirm your password" description="This is a secure area of the application. Please confirm your password before continuing.">
      <Head title="Confirm password" />

      <Form action={ConfirmablePasswordController.store()} disableWhileProcessing resetOnError resetOnSuccess>
        {({ processing, errors }) => (
          <FieldGroup>
            <InputField type="password" name="password" placeholder="Password" autoComplete="current-password" autoFocus error={errors.password} />
            <SubmitButton isSubmitting={processing}>{t('Confirm password')}</SubmitButton>
          </FieldGroup>
        )}
      </Form>
    </AuthLayout>
  );
}
