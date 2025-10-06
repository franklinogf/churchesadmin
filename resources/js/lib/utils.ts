import type { Missionary } from '@/types/models/missionary';
import type { Offering } from '@/types/models/offering';
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
export function offeringTypeIsMissionary(offeringType: Offering['offeringType']): offeringType is Missionary {
  return 'lastName' in offeringType;
}
export const cleanProperty = (property: string) => {
  return property.replaceAll(/_/g, ' ').replaceAll(/[.]/g, '->');
};
