import { type Transaction } from '@/types/models/transaction';
import type { CheckLayout } from './check-layout';

export interface Wallet {
  id: number;
  name: string;
  slug: string;
  description: string | null;
  bankName: string;
  bankAccountNumber: string;
  bankRoutingNumber: string;
  balance: string;
  balanceNumber: number;
  balanceFloat: string;
  balanceFloatNumber: number;
  createdAt: string;
  updatedAt: string;
  deletedAt: string | null;
  transactions?: Transaction[];
  transactionsCount?: number;
  checkLayout?: CheckLayout;
}
