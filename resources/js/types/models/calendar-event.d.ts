export type CalendarEventMorphClass = 'calendar_event';

export interface CalendarEvent {
  id: number;
  title: string;
  description: string | null;
  location: string | null;
  startAt: string;
  endAt: string;
  createdBy: number;
  createdAt: string;
  updatedAt: string;
  creator?: {
    id: number;
    name: string;
  };
}
