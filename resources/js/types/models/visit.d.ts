import type { FollowUpType } from '@/enums';
import type { Address } from './address';
import type { Member } from './member';

export interface Visit {
  id: number;
  name: string;
  lastName: string;
  email: string | null;
  phone: string;
  createdAt: string;
  updatedAt: string;
  deletedAt: string | null;
  address?: Address | null;
  followUps?: VisitFollowUp[];
  lastFollowUp?: VisitFollowUp | null;
}

export interface VisitFollowUp {
  id: number;
  visitId: number;
  visit?: Visit;
  memberId: number;
  member?: Member;
  type: FollowUpType;
  followUpDate: string;
  note: string | null;
  createdAt: string;
  updatedAt: string;
  deletedAt: string | null;
}
