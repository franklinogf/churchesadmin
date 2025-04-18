import { LanguageTranslations } from '@/types';

export interface Wallet {
  id: number;
  uuid: string;
  name: string;
  nameTranslations: LanguageTranslations;
  slug: string;
  description: string | null;
  descriptionTranslations: LanguageTranslations;
  balance: string;
  balanceInt: number;
  balanceFloat: string;
  balanceFloatNum: number;
  createdAt: string;
  updatedAt: string;
  deletedAt: string | null;
}
