import { type ClassValue, clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}
export const NavBar = [
    {
        label: 'Guide',
        url: '/',
    },
    {
        label: 'Pricing',
        url: '/',
    },
    {
        label: 'Log in',
        url: '/',
    },
] as const;
