import type { Permission, Role } from '@/types/models/user';

export function getUniquePermissions(roles: Role[], selectedRoles: string[]) {
    const uniquePermissionsMap: Record<string | number, Permission> = {};

    // Collect unique permissions from selected roles
    roles.forEach((role) => {
        if (selectedRoles.includes(role.name)) {
            role.permissions.forEach((permission) => {
                uniquePermissionsMap[permission.name] = permission;
            });
        }
    });

    return Object.values(uniquePermissionsMap);
}
