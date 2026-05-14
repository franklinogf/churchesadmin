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

export function formatDatetime(value: string | number | Date): string {
  return new Intl.DateTimeFormat(undefined, {
    dateStyle: 'medium',
    timeStyle: 'short',
  }).format(new Date(value));
}

export function formatRelativeDate(value: string | number | Date): string {
  const date = new Date(value);
  const diffInSeconds = Math.round((date.getTime() - Date.now()) / 1000);
  const divisions: { amount: number; unit: Intl.RelativeTimeFormatUnit }[] = [
    { amount: 60, unit: 'second' },
    { amount: 60, unit: 'minute' },
    { amount: 24, unit: 'hour' },
    { amount: 7, unit: 'day' },
    { amount: 4.34524, unit: 'week' },
    { amount: 12, unit: 'month' },
    { amount: Number.POSITIVE_INFINITY, unit: 'year' },
  ];

  let duration = diffInSeconds;

  for (const division of divisions) {
    if (Math.abs(duration) < division.amount) {
      return new Intl.RelativeTimeFormat(undefined, { numeric: 'auto' }).format(Math.round(duration), division.unit);
    }

    duration /= division.amount;
  }

  return formatDatetime(date);
}

export function offeringTypeIsMissionary(offeringType: Offering['offeringType']): offeringType is Missionary {
  return 'lastName' in offeringType;
}
export const cleanProperty = (property: string) => {
  return property.replaceAll(/_/g, ' ').replaceAll(/[.]/g, '->');
};
