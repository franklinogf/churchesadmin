import { Option } from '@/components/custom-ui/MultiSelect';
import { AddressRelationship } from '@/types/models/address';
import { RelationshipTag } from '@/types/models/tag';

export type MemberMorphClass = 'member';
export interface Member {
    id: number;
    name: string;
    lastName: string;
    email: string;
    phone: string;
    gender: string;
    dob: string;
    civilStatus: string;
    skills: RelationshipTag[];
    skillsCount?: number;
    categories: RelationshipTag[];
    categoriesCount?: number;
    address?: AddressRelationship | null;
    createdAt: string;
    updatedAt: string;
    deletedAt: string | null;
}

export type MemberFormData = {
    name: string;
    last_name: string;
    email: string;
    phone: string;
    dob: string;
    gender: string;
    civil_status: string;
    skills: Option[];
    categories: Option[];
};
