import { LanguageCode } from '@/enums';
import { UserPermission, UserRole } from '@/enums/user';

export interface User {
    id: number;
    name: string;
    email: string;
    roles?: Role[];
    permissions?: Permission[];
    createdAt: string;
    updatedAt: string;
}

export interface AuthenticatedUser {
    id: number;
    name: string;
    email: string;
    language: LanguageCode;
    emailVerifiedAt: string | null;
}

export interface Role {
    id: number;
    name: UserRole;
    label: string | null;
    permissions: Permission[];
}

export interface Permission {
    id: number;
    name: UserPermission;
    label: string | null;
}
