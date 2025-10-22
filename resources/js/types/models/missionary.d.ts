import type { Gender } from '@/enums/Gender';
import type { OfferingFrequency } from '@/enums/OfferingFrequency';
import { type AddressRelationship } from './address';
import type { Email, EmailPivot } from './email';

export interface Missionary {
  id: number;
  name: string;
  lastName: string;
  email: string | null;
  phone: string | null;
  gender: Gender;
  church: string | null;
  offering: number | null;
  offeringFrequency: OfferingFrequency | null;
  address?: AddressRelationship | null;
  createdAt: string;
  updatedAt: string;
  deletedAt: string | null;
  emailMessage?: EmailPivot;
  emails?: Email[];
}

export type MissionaryMorphClass = 'missionary';
