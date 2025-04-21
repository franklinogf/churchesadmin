import { LanguageTranslations } from '@/types';

export interface OfferingType {
  id: number;
  name: string;
  nameTranslations: LanguageTranslations;
  createdAt: string;
  updatedAt: string;
}
