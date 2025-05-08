import type { TransactionType as TransactionTypeEnum } from '@/enums';
import { type Wallet } from './wallet';

export type TransactionType = 'deposit' | 'withdraw';

type TransactionMeta = {
  type: `${TransactionTypeEnum}`;
};
export interface Transaction {
  id: number;
  uuid: string;
  wallet?: Wallet;
  type: TransactionType;
  amount: number;
  amountFloat: string;
  meta: TransactionMeta | null;
  confirmed: boolean;
  createdAt: string;
  updatedAt: string;
  deletedAt: string | null;
}
