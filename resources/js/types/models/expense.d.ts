import type { ExpenseType } from '@/types/models/expense-type';
import { type Member } from '@/types/models/member';
import type { Transaction } from '@/types/models/transaction';

export interface Expense {
  id: number;
  transaction: Transaction;
  expenseType: ExpenseType;
  member: Member | null;
  date: string;
  note: string | null;
}
