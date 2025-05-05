import { type Gender, type OfferingFrequency } from '@/enums';
import { type AddressRelationship } from './address';

export interface Missionary {
  id: number;
  name: string;
  lastName: string;
  email: string;
  phone: string;
  gender: Gender;
  church: string;
  offering: string;
  offeringFrequency: OfferingFrequency;
  address?: AddressRelationship | null;
  createdAt: string;
  updatedAt: string;
  deletedAt: string | null;
}

export type MissionaryFormData = {
  name: string;
  last_name: string;
  email: string;
  phone: string;
  gender: string;
  church: string;
  offering: number;
  offering_frequency: string;
};

export type MissionaryMorphClass = 'missionary';
