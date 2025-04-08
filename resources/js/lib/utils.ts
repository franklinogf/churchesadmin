import { LanguageTranslations, SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
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

export function emptyTranslations() {
    const {
        props: { availableLocales },
    } = usePage<SharedData>();
    return availableLocales.reduce((acc, { value }) => {
        acc[value as keyof LanguageTranslations] = '';
        return acc;
    }, {} as LanguageTranslations);
}
