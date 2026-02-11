import { type CivilStatus } from '@/enums/CivilStatus';
import { type Gender } from '@/enums/Gender';
import { type AddressRelationship } from '@/types/models/address';
import { type DeactivationCode } from '@/types/models/deactivation-code';
import type { Email, EmailPivot } from '@/types/models/email';
import { type TagRelationship } from '@/types/models/tag';

export type MemberMorphClass = 'member';
export interface Member {
  id: number;
  name: string;
  lastName: string;
  email: string | null;
  phone: string | null;
  gender: Gender;
  dob: string | null;
  baptismDate: string | null;
  civilStatus: CivilStatus;
  active: boolean;
  deactivationCodeId: number | null;
  deactivationCode?: DeactivationCode | null;
  skills: TagRelationship[];
  skillsCount?: number;
  categories: TagRelationship[];
  categoriesCount?: number;
  address?: AddressRelationship | null;
  createdAt: string;
  updatedAt: string;
  deletedAt: string | null;
  emailMessage?: EmailPivot;
  emails?: Email[];
}
