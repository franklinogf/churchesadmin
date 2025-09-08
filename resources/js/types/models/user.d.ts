import { type LanguageCode } from '@/enums';
import { type UserPermission, type UserRole } from '@/enums/user';

export interface User {
  id: string;
  name: string;
  email: string;
  roles?: Role[];
  permissions?: Permission[];
  createdAt: string;
  updatedAt: string;
}

export interface AuthenticatedUser {
  id: string;
  name: string;
  email: string;
  language: LanguageCode;
  roles: UserRole[];
  permissions: UserPermission[];
  emailVerifiedAt: string | null;
  timezone: string;
  timezoneCountry: string;
  currentYearId: number;
  currentYear: number;
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
