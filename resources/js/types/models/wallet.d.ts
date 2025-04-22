import { type LanguageTranslations } from '@/types';
import { type Transaction } from '@/types/models/transaction';
type WalletMeta = {
  bankName: string;
  bankAccountNumber: string;
  bankRoutingNumber: string;
};
export interface Wallet {
  id: number;
  uuid: string;
  meta: WalletMeta | null;
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
  transactions?: Transaction[];
  transactionsCount?: number;
}
