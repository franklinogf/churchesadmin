import { Tag } from './tags';

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
}
