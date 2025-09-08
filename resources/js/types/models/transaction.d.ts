import type { TransactionMetaType, TransactionType } from '@/enums';
import { type Wallet } from './wallet';

type TransactionMeta = {
  type: `${TransactionMetaType}`;
};

export interface Transaction {
  id: number;
  uuid: string;
  wallet?: Wallet;
  type: `${TransactionType}`;
  amount: number;
  amountFloat: string;
  meta: TransactionMeta | null;
  confirmed: boolean;
  createdAt: string;
  updatedAt: string;
  deletedAt: string | null;
}
