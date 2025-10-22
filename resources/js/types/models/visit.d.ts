import type { FollowUpType } from '@/enums/FollowUpType';
import type { AddressRelationship } from './address';
import type { EmailPivot } from './email';
import type { Member } from './member';

export interface Visit {
  id: number;
  name: string;
  lastName: string;
  email: string | null;
  phone: string | null;
  firstVisitDate: string | null;
  createdAt: string;
  updatedAt: string;
  deletedAt: string | null;
  address?: AddressRelationship | null;
  followUps?: VisitFollowUp[];
  lastFollowUp?: VisitFollowUp | null;
  emailMessage?: EmailPivot;
}

export interface VisitFollowUp {
  id: number;
  visitId: number;
  visit?: Visit;
  memberId: number;
  member?: Member;
  type: FollowUpType;
  followUpAt: string;
  notes: string | null;
  createdAt: string;
  updatedAt: string;
  deletedAt: string | null;
}
