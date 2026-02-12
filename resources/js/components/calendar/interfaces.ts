import type { CalendarEvent } from '@/types/models/calendar-event';
import type { User } from '@/types/models/user';

export type IUser = User;

export type IEvent = CalendarEvent;

export interface ICalendarCell {
  day: number;
  currentMonth: boolean;
  date: Date;
}
