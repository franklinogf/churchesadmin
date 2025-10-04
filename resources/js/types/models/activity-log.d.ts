import type { ModelMorphName } from '@/enums';
import type { Member } from '@/types/models/member';
import type { Missionary } from '@/types/models/missionary';
import type { User } from '@/types/models/user';
import type { Check } from './check';
import type { Expense } from './expense';
import type { Offering } from './offering';
import type { Visit } from './visit';
import type { Wallet } from './wallet';

type ActivityLogProperties = {
  attributes: Record<string, unknown>;
  old?: Record<string, unknown>;
};

type ActivityLogBase = {
  id: number;
  logName: string;
  event: string | null;
  description: string;
  subjectType: null;
  subjectId: null;
  causerType: string;
  causerId: string;
  properties: ActivityLogProperties | null;
  createdAt: string;
  updatedAt: string;
  causer: User;
};

interface MemberActivityLog extends ActivityLogBase {
  subjectType: `${ModelMorphName.MEMBER}`;
  subjectId: number;
  subject: Member;
}

interface MissionaryActivityLog extends ActivityLogBase {
  subjectType: `${ModelMorphName.MISSIONARY}`;
  subjectId: number;
  subject: Missionary;
}

interface VisitActivityLog extends ActivityLogBase {
  subjectType: `${ModelMorphName.VISIT}`;
  subjectId: number;
  subject: Visit;
}

interface UserActivityLog extends ActivityLogBase {
  subjectType: `${ModelMorphName.USER}`;
  subjectId: string;
  subject: User;
}

interface WalletActivityLog extends ActivityLogBase {
  subjectType: `${ModelMorphName.WALLET}`;
  subjectId: number;
  subject: Wallet;
}

interface OfferingActivityLog extends ActivityLogBase {
  subjectType: `${ModelMorphName.OFFERING}`;
  subjectId: number;
  subject: Offering;
}

interface ExpenseActivityLog extends ActivityLogBase {
  subjectType: `${ModelMorphName.EXPENSE}`;
  subjectId: number;
  subject: Expense;
}

interface CheckActivityLog extends ActivityLogBase {
  subjectType: `${ModelMorphName.CHECK}`;
  subjectId: number;
  subject: Check;
}

export type ActivityLog =
  | MemberActivityLog
  | MissionaryActivityLog
  | VisitActivityLog
  | UserActivityLog
  | WalletActivityLog
  | OfferingActivityLog
  | ExpenseActivityLog
  | CheckActivityLog;
