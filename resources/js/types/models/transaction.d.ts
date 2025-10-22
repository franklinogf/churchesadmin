import { type TransactionMetaType } from '@/enums/TransactionMetaType';
import { type TransactionType } from '@/enums/TransactionType';
import { type Wallet } from './wallet';

type TransactionMeta = {
  type: `${TransactionMetaType}`;
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
