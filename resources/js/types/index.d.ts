import { LanguageCode } from '@/enums';

import { UserPermission } from '@/enums/user';
import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';
import { AuthenticatedUser } from './models/user';

export interface Auth {
  user: AuthenticatedUser;
  permissions: string[];
}

export interface BreadcrumbItem {
  title: string;
  href?: string;
}

export interface NavGroup {
  title: string;
  items: NavItem[];
}

export interface NavItem {
  title: string;
  href: string;
  icon?: LucideIcon | null;
  isActive?: boolean;
  permissionNeeded?: UserPermission;
}

export type NavMenu = NavGroup | NavItem;

export interface SharedData {
  auth: Auth;
  ziggy: Config & { location: string };
  sidebarOpen: boolean;
  flash: {
    success: string | null;
    error: string | null;
  };
  availableLocales: {
    label: Locale;
    value: string;
  }[];
  [key: string]: unknown;
}

export interface SelectOption {
  label: string;
  value: string | number;
}

export type Locale = `${LanguageCode}`;

export type LanguageTranslations = Record<Locale, string | undefined>;
