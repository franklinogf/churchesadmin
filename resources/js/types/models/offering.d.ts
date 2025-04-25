import { type PaymentMethod } from '@/enums';
import { type Member } from '@/types/models/member';
import { type Missionary } from '@/types/models/missionary';
import { type OfferingType } from '@/types/models/offering-types';
import { type Transaction } from '@/types/models/transaction';

export interface Offering {
  id: number;
  transaction: Transaction;
  donor: Member | null;
  date: string;
  paymentMethod: `${PaymentMethod}`;
  offeringType: OfferingType | Missionary;
  note: string | null;
}
