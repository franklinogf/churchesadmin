import { type Option } from '@/components/custom-ui/MultiSelect';
import { Form } from '@/components/forms/Form';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { MultiSelectField } from '@/components/forms/inputs/MultiSelectField';
import { SwitchField } from '@/components/forms/inputs/SwitchField';
import { PageTitle } from '@/components/PageTitle';
import { ScrollArea } from '@/components/ui/scroll-area';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import { convertRolesToMultiselectOptions, getMultiselecOptionsValues } from '@/lib/mutliselect';
import type { Permission, Role, User } from '@/types/models/user';
import { useForm } from '@inertiajs/react';
import { getUniquePermissions } from './includes/functions';

type CreateForm = {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
  roles: Option[];
  additional_permissions: string[];
};

interface EditPageProps {
  user: User;
  permissions: Permission[];
  roles: Role[];
}

export default function Edit({ permissions, roles }: EditPageProps) {
  const { t } = useTranslations<string>();

  // const userPermissions = user.permissions?.map((permission) => permission.name);
  const { data, setData, errors, post, processing, transform } = useForm<CreateForm>({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    roles: [],
    additional_permissions: [],
  });

  transform((data) => ({
    ...data,
    roles: getMultiselecOptionsValues(data.roles),
  }));

  function handleSubmit() {
    post(route('users.store'));
  }
  const selectedRoles = data.roles.map((role) => role.value);
  const selectedRolesPermissions = getUniquePermissions(roles, selectedRoles);

  return (
    <AppLayout title={t('Users')}>
      <PageTitle>{t('Add :model', { model: t('User') })}</PageTitle>
      <div className="mt-2 flex w-full items-center justify-center">
        <Form className="w-full max-w-2xl" onSubmit={handleSubmit} isSubmitting={processing}>
          <InputField required label={t('Name')} value={data.name} error={errors.name} onChange={(value) => setData('name', value)} />
          <InputField
            required
            label={t('Email')}
            type="email"
            value={data.email}
            error={errors.email}
            onChange={(value) => setData('email', value)}
          />
          <FieldsGrid>
            <InputField
              required
              label={t('Password')}
              //   type="password"
              value={data.password}
              error={errors.password}
              onChange={(value) => setData('password', value)}
            />
            <InputField
              required
              label={t('Password confirmation')}
              //   type="password"
              value={data.password_confirmation}
              error={errors.password_confirmation}
              onChange={(value) => setData('password_confirmation', value)}
            />
          </FieldsGrid>
          <MultiSelectField
            required
            label={t('Roles')}
            options={convertRolesToMultiselectOptions(roles)}
            value={data.roles}
            error={errors.roles}
            onChange={(value) => {
              setData('additional_permissions', []);
              setData('roles', value);
            }}
          />

          <div className="space-y-4">
            <p className="text-lg font-medium">{t('Assigned permissions')}</p>
            <ScrollArea className="h-60 w-full">
              <div className="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-4">
                {permissions.map((permission) => {
                  const existsOnRoles = selectedRolesPermissions.some((p) => p.name === permission.name);
                  const value = data.additional_permissions.includes(permission.name) || existsOnRoles;

                  return (
                    <SwitchField
                      disabled={existsOnRoles}
                      key={permission.id}
                      label={permission.label ?? ''}
                      value={value}
                      onChange={(checked) => {
                        if (checked) {
                          setData('additional_permissions', [...data.additional_permissions, permission.name.toString()]);
                        } else {
                          setData(
                            'additional_permissions',
                            data.additional_permissions.filter((id) => id !== permission.name.toString()),
                          );
                        }
                      }}
                    />
                  );
                })}
              </div>
            </ScrollArea>
          </div>
        </Form>
      </div>
    </AppLayout>
  );
}
