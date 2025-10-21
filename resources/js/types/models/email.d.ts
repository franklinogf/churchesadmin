import type { EmailStatus, ModelMorphName } from '@/enums';
import type { Media } from '@/types/models/media';
import type { Member } from '@/types/models/member';
import type { Missionary } from '@/types/models/missionary';
import type { User } from '@/types/models/user';
import type { Visit } from '@/types/models/visit';

export interface EmailBase {
  id: string;
  subject: string;
  body: string;
  senderId: string;
  sender?: User;
  replyTo: string;
  status: `${EmailStatus}`;
  sentAt: string | null;
  errorMessage: string | null;
  createdAt: string;
  updatedAt: string;
  attachments?: Media[];
  attachmentsCount?: number;
  message?: EmailPivot;
}

export interface EmailMembers extends EmailBase {
  recipientsType: ModelMorphName.MEMBER;
  recipients: Member[];
}

export interface EmailMissionaries extends EmailBase {
  recipientsType: ModelMorphName.MISSIONARY;
  recipients: Missionary[];
}

export interface EmailVisitors extends EmailBase {
  recipientsType: ModelMorphName.VISIT;
  recipients: Visit[];
}

export interface EmailPivot {
  id: number;
  sentAt: string | null;
  status: EmailStatus;
  errorMessage: string | null;
  createdAt: string;
  updatedAt: string;
}

export type Email = EmailMembers | EmailMissionaries | EmailVisitors;
