export interface Member {
    id: number;
    name: string;
    last_name: string;
    email: string;
    phone: string;
    gender: string;
    dob: string;
    civil_status: string;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
}
// export interface MemberWithAddress extends Member
