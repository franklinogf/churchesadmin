import type { CalendarEvent } from '@/types/models/calendar-event';
import { addDays, addMonths, endOfMonth, endOfWeek, format, isSameDay, isSameMonth, startOfMonth, startOfWeek, subMonths } from 'date-fns';

export interface CalendarDay {
  date: Date;
  isCurrentMonth: boolean;
  isToday: boolean;
  events: CalendarEvent[];
}

/**
 * Get all days to display in a month view calendar grid (including padding days)
 */
export function getMonthDays(date: Date): CalendarDay[] {
  const monthStart = startOfMonth(date);
  const monthEnd = endOfMonth(date);
  const startDate = startOfWeek(monthStart);
  const endDate = endOfWeek(monthEnd);

  const days: CalendarDay[] = [];
  let currentDate = startDate;
  const today = new Date();

  while (currentDate <= endDate) {
    days.push({
      date: currentDate,
      isCurrentMonth: isSameMonth(currentDate, date),
      isToday: isSameDay(currentDate, today),
      events: [],
    });
    currentDate = addDays(currentDate, 1);
  }

  return days;
}

/**
 * Get 7 days for week view
 */
export function getWeekDays(date: Date): CalendarDay[] {
  const weekStart = startOfWeek(date);
  const days: CalendarDay[] = [];
  const today = new Date();

  for (let i = 0; i < 7; i++) {
    const currentDate = addDays(weekStart, i);
    days.push({
      date: currentDate,
      isCurrentMonth: true,
      isToday: isSameDay(currentDate, today),
      events: [],
    });
  }

  return days;
}

/**
 * Get hour slots for day/week view (0-23)
 */
export function getHourSlots(): number[] {
  return Array.from({ length: 24 }, (_, i) => i);
}

/**
 * Filter events for a specific date
 */
export function getEventsForDate(events: CalendarEvent[], date: Date): CalendarEvent[] {
  return events.filter((event) => isEventOnDate(event, date));
}

/**
 * Check if an event occurs on a specific date
 */
export function isEventOnDate(event: CalendarEvent, date: Date): boolean {
  const eventStart = new Date(event.startAt);
  const eventEnd = new Date(event.endAt);

  // Reset time portions for date comparison
  const dateOnly = new Date(date.getFullYear(), date.getMonth(), date.getDate());
  const startOnly = new Date(eventStart.getFullYear(), eventStart.getMonth(), eventStart.getDate());
  const endOnly = new Date(eventEnd.getFullYear(), eventEnd.getMonth(), eventEnd.getDate());

  // Check if date is within the event range (inclusive)
  return dateOnly >= startOnly && dateOnly <= endOnly;
}

/**
 * Format event time for display
 */
export function formatEventTime(event: CalendarEvent): string {
  const start = new Date(event.startAt);
  const end = new Date(event.endAt);

  // Check if it's an all-day event (time is 00:00:00)
  const isAllDay =
    start.getHours() === 0 &&
    start.getMinutes() === 0 &&
    start.getSeconds() === 0 &&
    end.getHours() === 0 &&
    end.getMinutes() === 0 &&
    end.getSeconds() === 0;

  if (isAllDay) {
    if (isSameDay(start, end)) {
      return 'All day';
    }
    return `${format(start, 'MMM d')} - ${format(end, 'MMM d')}`;
  }

  if (isSameDay(start, end)) {
    return `${format(start, 'h:mm a')} - ${format(end, 'h:mm a')}`;
  }

  return `${format(start, 'MMM d h:mm a')} - ${format(end, 'MMM d h:mm a')}`;
}

/**
 * Calculate event position in grid (for day/week views)
 */
export function calculateEventPosition(event: CalendarEvent): { top: number; height: number } {
  const start = new Date(event.startAt);
  const end = new Date(event.endAt);

  // Check if it's an all-day event
  const isAllDay = start.getHours() === 0 && start.getMinutes() === 0 && start.getSeconds() === 0;

  if (isAllDay) {
    return { top: 0, height: 60 };
  }

  // Each hour is 60px
  const hourHeight = 60;

  // Calculate top position based on start time
  const top = start.getHours() * hourHeight + (start.getMinutes() / 60) * hourHeight;

  // Calculate duration in milliseconds
  const duration = end.getTime() - start.getTime();
  const durationHours = duration / (1000 * 60 * 60);
  const height = durationHours * hourHeight;

  return { top, height: Math.max(height, 30) }; // Minimum 30px height
}

/**
 * Navigate to previous month
 */
export function getPreviousMonth(date: Date): Date {
  return subMonths(date, 1);
}

/**
 * Navigate to next month
 */
export function getNextMonth(date: Date): Date {
  return addMonths(date, 1);
}

/**
 * Get week number in month (for week navigation)
 */
export function getPreviousWeek(date: Date): Date {
  return addDays(date, -7);
}

export function getNextWeek(date: Date): Date {
  return addDays(date, 7);
}

/**
 * Get previous/next day
 */
export function getPreviousDay(date: Date): Date {
  return addDays(date, -1);
}

export function getNextDay(date: Date): Date {
  return addDays(date, 1);
}

/**
 * Format month and year for header display
 */
export function formatMonthYear(date: Date): string {
  return format(date, 'MMMM yyyy');
}

/**
 * Format week range for header display
 */
export function formatWeekRange(date: Date): string {
  const weekStart = startOfWeek(date);
  const weekEnd = endOfWeek(date);
  return `${format(weekStart, 'MMM d')} - ${format(weekEnd, 'MMM d, yyyy')}`;
}

/**
 * Format day for header display
 */
export function formatDayHeader(date: Date): string {
  return format(date, 'EEEE, MMMM d, yyyy');
}

/**
 * Check if two dates are the same day
 */
export function isSameDateAs(date1: Date, date2: Date): boolean {
  return isSameDay(date1, date2);
}

/**
 * Add events to calendar days
 */
export function addEventsToDays(days: CalendarDay[], events: CalendarEvent[]): CalendarDay[] {
  return days.map((day) => ({
    ...day,
    events: getEventsForDate(events, day.date),
  }));
}

/**
 * Check if two events overlap in time
 */
export function doEventsOverlap(event1: CalendarEvent, event2: CalendarEvent): boolean {
  const start1 = new Date(event1.startAt).getTime();
  const end1 = new Date(event1.endAt).getTime();
  const start2 = new Date(event2.startAt).getTime();
  const end2 = new Date(event2.endAt).getTime();

  return start1 < end2 && start2 < end1;
}

/**
 * Calculate layout positions for overlapping events
 */
export function calculateEventLayout(events: CalendarEvent[]): Map<number, { column: number; totalColumns: number }> {
  const layout = new Map<number, { column: number; totalColumns: number }>();

  if (events.length === 0) return layout;

  // Sort events by start time
  const sortedEvents = [...events].sort((a, b) => new Date(a.startAt).getTime() - new Date(b.startAt).getTime());

  // Track columns and overlapping groups
  const columns: CalendarEvent[][] = [];

  sortedEvents.forEach((event) => {
    // Find the first column where this event doesn't overlap with existing events
    let placed = false;
    for (let i = 0; i < columns.length; i++) {
      const column = columns[i];
      const hasOverlap = column?.some((existingEvent) => doEventsOverlap(event, existingEvent));

      if (!hasOverlap) {
        column?.push(event);
        placed = true;

        // Find how many columns this event group needs
        const eventIndex = events.indexOf(event);
        const totalColumns = columns.filter((col) => col.some((e) => doEventsOverlap(e, event))).length + 1;

        layout.set(eventIndex, { column: i, totalColumns });
        break;
      }
    }

    // If no suitable column found, create a new one
    if (!placed) {
      columns.push([event]);
      const eventIndex = events.indexOf(event);
      layout.set(eventIndex, { column: columns.length - 1, totalColumns: columns.length });
    }
  });

  // Update totalColumns for all events in overlapping groups
  columns.forEach((column) => {
    column.forEach((event) => {
      const eventIndex = events.indexOf(event);
      const overlappingColumns = columns.filter((col) => col.some((e) => doEventsOverlap(e, event)));
      const current = layout.get(eventIndex);
      if (current) {
        layout.set(eventIndex, { ...current, totalColumns: overlappingColumns.length });
      }
    });
  });

  return layout;
}
