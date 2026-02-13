import UserController from '@/actions/App/Http/Controllers/UserController';
import { type Option } from '@/components/custom-ui/MultiSelect';
import { Form } from '@/components/forms/Form';
import { InputField } from '@/components/forms/inputs/InputField';
import { MultiSelectField } from '@/components/forms/inputs/MultiSelectField';
import { SwitchField } from '@/components/forms/inputs/SwitchField';
import { PageTitle } from '@/components/PageTitle';
import { ScrollArea } from '@/components/ui/scroll-area';
import { TenantRole } from '@/enums/TenantRole';
import { useTranslations } from '@/hooks/use-translations';
import { useUser } from '@/hooks/use-user';
import AppLayout from '@/layouts/app-layout';
import { convertRolesToMultiselectOptions, getMultiselecOptionsValues } from '@/lib/mutliselect';
import type { Permission, Role, User } from '@/types/models/user';
import { useForm } from '@inertiajs/react';
import { getUniquePermissions } from './includes/functions';

type EditForm = {
  name: string;
  email: string;
  roles: Option[];
  additional_permissions: string[];
};

interface EditPageProps {
  user: User;
  permissions: Permission[];
  roles: Role[];
}

export default function Edit({ user, permissions, roles }: EditPageProps) {
  const { t } = useTranslations();
  const { hasRole } = useUser();

  const userPermissions = user.permissions?.map((permission) => permission.name);
  const { data, setData, errors, submit, processing, transform } = useForm<EditForm>({
    name: user.name,
    email: user.email,
    roles: convertRolesToMultiselectOptions(user.roles || []),
    additional_permissions: userPermissions || [],
  });

  transform((data) => ({
    ...data,
    roles: getMultiselecOptionsValues(data.roles),
  }));

  function handleSubmit() {
    submit(UserController.update(user.id));
  }
  const selectedRoles = data.roles.map((role) => role.value);
  const selectedRolesPermissions = getUniquePermissions(roles, selectedRoles);

  return (
    <AppLayout
      title={t('Users')}
      breadcrumbs={[{ title: t('Users'), href: UserController.index().url }, { title: t('Edit :model', { model: t('User') }) }]}
    >
      <PageTitle>{t('Edit :model', { model: t('User') })}</PageTitle>
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
          {hasRole(TenantRole.SUPER_ADMIN) && (
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
          )}

          {(hasRole(TenantRole.SUPER_ADMIN) || hasRole(TenantRole.ADMIN)) && (
            <div className="space-y-4">
              <p className="text-lg font-medium">{t('Assigned permissions')}</p>
              <ScrollArea className="h-60 w-full">
                <div className="flex flex-col flex-wrap gap-2">
                  {permissions.map((permission) => {
                    const existsOnRoles = selectedRolesPermissions.some((p) => p.name === permission.name);
                    const value = data.additional_permissions.includes(permission.name) || existsOnRoles;

                    return (
                      <SwitchField
                        disabled={existsOnRoles}
                        key={permission.id}
                        label={permission.label ?? ''}
                        checked={value}
                        onCheckedChange={(checked) => {
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
          )}
        </Form>
      </div>
    </AppLayout>
  );
}
