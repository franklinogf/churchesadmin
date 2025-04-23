import { type Member, type MemberMorphClass } from '@/types/models/member';
import { type Missionary, type MissionaryMorphClass } from './missionary';

type MorphClasses = MemberMorphClass | MissionaryMorphClass;
type AddressOwner = Member | Missionary;

export interface Address {
  id: number;
  ownerId: number;
  ownerType: MorphClasses;
  owner: AddressOwner;
  address1: string;
  address2: string;
  city: string;
  state: string;
  country: string;
  zipCode: string;
  createdAt: string;
  updatedAt: string;
}

export type AddressRelationship = Omit<Address, 'createdAt' | 'updatedAt' | 'ownerId' | 'ownerType' | 'owner'>;

export type AddressFormData = {
  address_1: string;
  address_2: string;
  city: string;
  state: string;
  country: string;
  zip_code: string;
};
