import { OfferingType } from '@/enums';
import { Wallet } from '@/type/models/wallet';
import { Member, MemberMorphClass } from '@/types/models/member';
import { Missionary, MissionaryMorphClass } from '@/types/models/missionary';

export type TransactionType = 'deposit' | 'withdraw';

export type TransactionMeta = {
  payerId: number;
  offeringType: `${OfferingType}`;
  date: string;
  message: string;
};

export interface Transaction {
  id: number;
  uuid: string;
  payerType?: MemberMorphClass | MissionaryMorphClass | null;
  payer?: Member | Missionary | null;
  type: TransactionType;
  amount: number;
  amountFloat: string;
  confirmed: boolean;
  meta: TransactionMeta | null;
  createdAt: string;
  updatedAt: string;
  deletedAt: string | null;
}

export interface OfferingTransaction {
  id: number;
  uuid: string;
  wallet: Wallet;
  type: TransactionMeta['type'];
  date: TransactionMeta['date'];
  payer?: Member;
  amount: number;
  amountFloat: string;
  confirmed: boolean;
  createdAt: string;
  updatedAt: string;
  deletedAt: string | null;
}
