import { type TenantPermission } from '@/enums/TenantPermission';
import { type TenantRole } from '@/enums/TenantRole';
import { type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';

export function useUser() {
  const {
    props: {
      auth: { user },
    },
  } = usePage<SharedData>();

  const can = (permission: TenantPermission) => {
    return user.permissions.includes(permission);
  };

  const hasRole = (role: TenantRole) => {
    return user.roles.includes(role);
  };

  return { user, can, hasRole };
}
