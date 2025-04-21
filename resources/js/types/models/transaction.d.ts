import { Wallet } from './wallet';

export type TransactionType = 'deposit' | 'withdraw';

export interface Transaction {
  id: number;
  uuid: string;
  wallet?: Wallet;
  type: TransactionType;
  amount: number;
  amountFloat: string;
  meta: Record<string, unknown> | null;
  confirmed: boolean;
  createdAt: string;
  updatedAt: string;
  deletedAt: string | null;
}
