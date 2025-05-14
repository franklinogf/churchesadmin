import { type LanguageCode } from '@/enums';

import { type UserPermission } from '@/enums/user';
import { type LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';
import type { Church } from './models/church';
import type { AuthenticatedUser } from './models/user';

export interface Auth {
  user: AuthenticatedUser;
  permissions: string[];
}

export interface BreadcrumbItem {
  title: string;
  href?: string;
}

type BaseNavMenu = { title: string };

export type NavItem = BaseNavMenu & {
  href: string;
  icon?: LucideIcon | null;
  isActive?: boolean;
  permissionNeeded?: UserPermission;
};

export type NavGroup = BaseNavMenu & {
  items: NavItem[];
};

export interface SharedData {
  auth: Auth;
  ziggy: Config & { location: string };
  sidebarOpen: boolean;
  flash: {
    success: string | null;
    error: string | null;
    message: string | null;
  };
  availableLocales: {
    label: Locale;
    value: string;
  }[];
  appName: string;
  environment: 'production' | 'local' | 'staging';
  church: Church | null;
  [key: string]: unknown;
}

export type SelectOption = {
  label: string;
  value: string | number;
};

export type SelectOptionWithModel = {
  heading: string;
  model: string;
  options: SelectOption[];
};

export type Locale = `${LanguageCode}`;

export type LanguageTranslations = Record<Locale, string | undefined>;

export type CheckFieldName = `${CheckLayoutFieldName}`;
