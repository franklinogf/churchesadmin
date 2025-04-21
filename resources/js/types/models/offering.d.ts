import { PaymentMethod } from '@/enums';
import { Member } from '@/types/models/member';
import { Missionary } from '@/types/models/missionary';
import { OfferingType } from '@/types/models/offering-types';
import { Transaction } from '@/types/models/transaction';

export interface Offering {
  id: number;
  transaction: Transaction;
  donor: Member | null;
  recipient: Missionary | null;
  date: string;
  paymentMethod: `${PaymentMethod}`;
  offeringType: OfferingType;
  note: string | null;
}
