import type { LanguageCode } from '@/enums/LanguageCode';

export type * from '@/types/models/activity-log';
export type * from '@/types/models/address';
export type * from '@/types/models/calendar-event';
export type * from '@/types/models/check';
export type * from '@/types/models/check-layout';
export type * from '@/types/models/church';
export type * from '@/types/models/current-year';
export type * from '@/types/models/deactivation-code';
export type * from '@/types/models/email';
export type * from '@/types/models/expense';
export type * from '@/types/models/expense-type';
export type * from '@/types/models/media';
export type * from '@/types/models/member';
export type * from '@/types/models/missionary';
export type * from '@/types/models/offering';
export type * from '@/types/models/offering-type';
export type * from '@/types/models/tag';
export type * from '@/types/models/transaction';
export type * from '@/types/models/user';
export type * from '@/types/models/visit';
export type * from '@/types/models/wallet';

export type EnumColumn<T extends string | number> = {
  value: T;
  label: string;
};

export type PaginatedLinks = {
  first: string;
  last: string;
  prev: string | null;
  next: string | null;
};

export type MetaLink = {
  url: string | null;
  label: string;
  page: number | null;
  active: boolean;
};

export type PaginatedMeta = {
  current_page: number;
  from: number;
  last_page: number;
  links: MetaLink[];
  path: string;
  per_page: number;
  to: number;
  total: number;
};
export type PaginatedModelMetaLinks = {
  links: PaginatedLinks;
  meta: PaginatedMeta;
};
export type PaginatedModel<T> = PaginatedModelMetaLinks & {
  data: T[];
};

export type Translatable = Record<LanguageCode, string>;
