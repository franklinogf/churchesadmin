import type { CheckType } from '@/enums/CheckType';
import type { ExpenseType } from './expense-type';
import type { Member } from './member';
import type { Transaction } from './transaction';

interface Check {
  id: number;
  transaction: Transaction;
  member: Member;
  expenseType: ExpenseType;
  checkNumber: string | null;
  date: string;
  type: CheckType;
  note: string | null;
  createdAt: string;
  updatedAt: string;
}
