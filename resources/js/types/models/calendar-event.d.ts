import type { CalendarEventColorEnum } from '@/enums/CalendarEventColorEnum';
import type { User } from '@/types/models/user';

export type CalendarEventMorphClass = 'calendar_event';

export interface CalendarEvent {
  id: number;
  title: string;
  description: string | null;
  location: string | null;
  color: CalendarEventColorEnum;
  startAt: string;
  endAt: string;
  createdBy: User['id'];
  createdAt: string;
  updatedAt: string;
  creator?: User;
}
