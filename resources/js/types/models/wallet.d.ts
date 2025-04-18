import { LanguageTranslations } from '@/types';
import { Member, MemberMorphClass } from '@/types/models/member';
import { Missionary, MissionaryMorphClass } from '@/types/models/missionary';

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
  transactions?: Transaction[];
  transactionsCount?: number;
}

export type TransactionType = 'deposit' | 'withdraw';
export interface Transaction {
  id: number;
  uuid: string;
  payerType?: MemberMorphClass | MissionaryMorphClass | null;
  payer?: Member | Missionary | null;
  type: TransactionType;
  amount: number;
  amountFloat: string;
  confirmed: boolean;
  meta: Record<string, string | number> | null;
  createdAt: string;
  updatedAt: string;
  deletedAt: string | null;
}
