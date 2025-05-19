import { type UserPermission, type UserRole } from '@/enums/user';
import { type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';

export function useUser() {
  const {
    props: {
      auth: { user },
    },
  } = usePage<SharedData>();

  const can = (permission: UserPermission) => {
    return user.permissions.includes(permission);
  };

  const hasRole = (role: UserRole) => {
    return user.roles.includes(role);
  };

  return { user, can, hasRole };
}
