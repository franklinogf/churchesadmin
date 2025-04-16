import { clsx, type ClassValue } from 'clsx';

import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

export function ucwords(str: string): string {
  return str
    .trim()
    .toLowerCase()
    .split(' ')
    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ');
}

export function isImage(mime_type: string) {
  return mime_type.startsWith('image/');
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
