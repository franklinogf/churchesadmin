import { UserPermission } from '@/enums/user';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';

export function usePermissions() {
    const {
        props: {
            auth: { permissions },
        },
    } = usePage<SharedData>();

    function userCan(permission: UserPermission) {
        return permissions.includes(permission);
    }

    return { userCan };
}
