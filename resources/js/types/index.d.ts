import { type LanguageCode } from '@/enums';

import type { ChurchFeature } from '@/enums/ChurchFeature';
import { type UserPermission } from '@/enums/user';
import type { RouteDefinition } from '@/wayfinder';
import { type LucideIcon } from 'lucide-react';
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
  href: string | RouteDefinition<'get'>;
  icon?: LucideIcon | null;
  isActive?: boolean;
  permissionNeeded?: UserPermission;
};

export type NavGroup = BaseNavMenu & {
  items: NavItem[];
};

export interface SharedData {
  auth: Auth;
  sidebarOpen: boolean;
  flash: {
    success: string | null;
    error: string | null;
    message: string | null;
  };
  appName: string;
  environment: 'production' | 'local' | 'staging';
  church: Church | null;
  features: Record<ChurchFeature, boolean>;
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

export type PdfColumn = { name: string; label: string; selected: boolean };
