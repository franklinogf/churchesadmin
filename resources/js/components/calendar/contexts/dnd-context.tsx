'use client';

import CalendarEventRescheduleController from '@/actions/App/Http/Controllers/CalendarEventRescheduleController';
import { useCalendar } from '@/components/calendar/contexts/calendar-context';
import type { IEvent } from '@/components/calendar/interfaces';
import { router } from '@inertiajs/react';
import React, { createContext, type ReactNode, useCallback, useContext, useMemo, useRef, useState } from 'react';
interface DragDropContextType {
  draggedEvent: IEvent | null;
  isDragging: boolean;
  startDrag: (event: IEvent) => void;
  endDrag: () => void;
  handleEventDrop: (date: Date, hour?: number, minute?: number) => void;
}

interface DndProviderProps {
  children: ReactNode;
}

const DragDropContext = createContext<DragDropContextType | undefined>(undefined);

export function DndProvider({ children }: DndProviderProps) {
  const { setEvents, updateEvent, events } = useCalendar();
  const [dragState, setDragState] = useState<{
    draggedEvent: IEvent | null;
    isDragging: boolean;
  }>({ draggedEvent: null, isDragging: false });

  const onEventDroppedRef = useRef<((event: IEvent, newStartDate: Date, newEndDate: Date) => void) | null>(null);

  const startDrag = useCallback((event: IEvent) => {
    setDragState({ draggedEvent: event, isDragging: true });
  }, []);

  const endDrag = useCallback(() => {
    setDragState({ draggedEvent: null, isDragging: false });
  }, []);

  const calculateNewDates = useCallback((event: IEvent, targetDate: Date, hour?: number, minute?: number) => {
    const originalStart = new Date(event.startAt);
    const originalEnd = new Date(event.endAt);
    const duration = originalEnd.getTime() - originalStart.getTime();

    const newStart = new Date(targetDate);
    if (hour !== undefined) {
      newStart.setHours(hour, minute || 0, 0, 0);
    } else {
      newStart.setHours(originalStart.getHours(), originalStart.getMinutes(), 0, 0);
    }

    return {
      newStart,
      newEnd: new Date(newStart.getTime() + duration),
    };
  }, []);

  const isSamePosition = useCallback((date1: Date, date2: Date) => {
    return date1.getTime() === date2.getTime();
  }, []);

  const handleEventDrop = useCallback(
    (targetDate: Date, hour?: number, minute?: number) => {
      const { draggedEvent } = dragState;
      if (!draggedEvent) return;

      const { newStart, newEnd } = calculateNewDates(draggedEvent, targetDate, hour, minute);
      const originalStart = new Date(draggedEvent.startAt);

      // Check if dropped in same position
      if (isSamePosition(originalStart, newStart)) {
        endDrag();
        return;
      }

      // Instantly update event
      const callback = onEventDroppedRef.current;
      if (callback) {
        callback(draggedEvent, newStart, newEnd);
      }
      endDrag();
    },
    [dragState, calculateNewDates, isSamePosition, endDrag],
  );

  // Default event update handler
  const handleEventUpdate = useCallback(
    (event: IEvent, newStartDate: Date, newEndDate: Date) => {
      const oldEvents = events;
      updateEvent(event.id, { startAt: newStartDate.toISOString(), endAt: newEndDate.toISOString() });
      router.visit(CalendarEventRescheduleController(event.id), {
        data: { start_at: newStartDate.toISOString(), end_at: newEndDate.toISOString() },
        showProgress: false,
        only: ['events'],
        onError: () => {
          setEvents(oldEvents);
        },
      });
    },
    [setEvents, updateEvent, events],
  );

  // Set default callback
  React.useEffect(() => {
    onEventDroppedRef.current = handleEventUpdate;
  }, [handleEventUpdate]);

  const contextValue = useMemo(
    () => ({
      draggedEvent: dragState.draggedEvent,
      isDragging: dragState.isDragging,
      startDrag,
      endDrag,
      handleEventDrop,
    }),
    [dragState, startDrag, endDrag, handleEventDrop],
  );

  return <DragDropContext.Provider value={contextValue}>{children}</DragDropContext.Provider>;
}

export function useDragDrop() {
  const context = useContext(DragDropContext);
  if (!context) {
    throw new Error('useDragDrop must be used within a DragDropProvider');
  }
  return context;
}
