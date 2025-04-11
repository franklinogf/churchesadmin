import { UserPermission, UserRole } from '@/enums/user';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';

export function usePermissions() {
  const {
    props: {
      auth: {
        user: { permissions, roles },
      },
    },
  } = usePage<SharedData>();

  const can = (permission: UserPermission) => {
    return permissions.includes(permission);
  };

  const hasRole = (role: UserRole) => {
    return roles.includes(role);
  };

  return { can, hasRole };
}
