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
  slug: string;
  description: string | null;
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
