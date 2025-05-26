import type { EmailStatus, ModelMorphName } from '@/enums';
import type { Media } from './media';
import type { User } from './user';

interface Email {
  id: string;
  subject: string;
  body: string;
  senderId: string;
  sender?: User;
  recipientsType: ModelMorphName.MEMBER | ModelMorphName.MISSIONARY;
  replyTo: string;
  status: `${EmailStatus}`;
  sentAt: string;
  errorMessage: string | null;
  createdAt: string;
  updatedAt: string;
  attachments?: Media[];
  attachmentsCount?: number;
}
