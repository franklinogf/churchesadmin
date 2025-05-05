import { type Gender, type OfferingFrequency } from '@/enums';
import { type AddressRelationship } from './address';

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
}

export type MissionaryMorphClass = 'missionary';
