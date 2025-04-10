import { Tag } from './tag';

export interface Member {
    id: number;
    name: string;
    lastName: string;
    email: string;
    phone: string;
    gender: string;
    dob: string;
    civilStatus: string;
    createdAt: string;
    updatedAt: string;
    skills: Tag[];
    categories: Tag[];
    address: {
        address1: string;
        address2: string;
        city: string;
        state: string;
        country: string;
        zipCode: string;
    };
}
