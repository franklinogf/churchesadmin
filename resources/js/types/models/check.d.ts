import type { CheckType } from '@/enums';
import type { Member } from './member';
import type { Transaction } from './transaction';

interface Check {
  id: number;
  member: Member;
  transaction: Transaction;
  date: string; // ISO date string
  type: `${CheckType}`; // e.g., 'deposit', 'withdrawal'
  createdAt: string;
  updatedAt: string;
}
