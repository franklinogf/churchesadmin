import { type LanguageTranslations } from '@/types';

export interface Tag {
  id: number;
  name: string;
  nameTranslations: LanguageTranslations;
  slug: string;
  slugTranslations: LanguageTranslations;
  type: string;
  orderColumn: number;
  isRegular: boolean;
  createdAt: string;
  updatedAt: string;
}

export type TagRelationship = Pick<Tag, 'id' | 'name' | 'slug'>;
