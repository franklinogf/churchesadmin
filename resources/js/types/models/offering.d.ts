import { type PaymentMethod } from '@/enums';
import { type Member } from '@/types/models/member';
import { type Missionary, type MissionaryMorphClass } from '@/types/models/missionary';
import { type OfferingType, type OfferingTypeMorphClass } from '@/types/models/offering-type';
import { type Transaction } from '@/types/models/transaction';

export interface Offering {
  id: number;
  transaction: Transaction;
  donor: Member | null;
  date: string;
  paymentMethod: `${PaymentMethod}`;
  offeringType: OfferingType | Missionary;
  offeringTypeModel: MissionaryMorphClass | OfferingTypeMorphClass;
  note: string | null;
}

export type OfferingGroupedByDate = {
  date: string;
  total: string;
} & Record<PaymentMethod, string>;
