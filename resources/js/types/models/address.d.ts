import { MemberMorphClass } from '@/types/models/member';

type MorphClasses = MemberMorphClass;

export interface Address {
    id: number;
    ownerId: number;
    ownerType: MorphClasses;
    owner: Member;
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
