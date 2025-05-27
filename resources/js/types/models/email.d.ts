import type { EmailStatus, ModelMorphName } from '@/enums';
import type { Media } from './media';
import type { Member } from './member';
import type { Missionary } from './missionary';
import type { User } from './user';

export interface Email {
  id: string;
  subject: string;
  body: string;
  senderId: string;
  sender?: User;
  replyTo: string;
  recipientsType: ModelMorphName.MEMBER | ModelMorphName.MISSIONARY;
  members?: Member[];
  missionaries?: Missionary[];
  status: `${EmailStatus}`;
  sentAt: string | null;
  errorMessage: string | null;
  createdAt: string;
  updatedAt: string;
  attachments?: Media[];
  attachmentsCount?: number;
  message?: EmailPivot;
}
export interface EmailPivot {
  id: number;
  sentAt: string | null;
  status: `${EmailStatus}`;
  errorMessage: string | null;
  createdAt: string;
  updatedAt: string;
}
