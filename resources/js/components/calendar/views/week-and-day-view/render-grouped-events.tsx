import { getEventBlockStyle } from '@/components/calendar/helpers';
import type { IEvent } from '@/components/calendar/interfaces';
import { EventBlock } from '@/components/calendar/views/week-and-day-view/event-block';
import { areIntervalsOverlapping, parseISO } from 'date-fns';

interface RenderGroupedEventsProps {
  groupedEvents: IEvent[][];
  day: Date;
}

export function RenderGroupedEvents({ groupedEvents, day }: RenderGroupedEventsProps) {
  return groupedEvents.map((group, groupIndex) =>
    group.map((event) => {
      let style = getEventBlockStyle(event, day, groupIndex, groupedEvents.length);
      const hasOverlap = groupedEvents.some(
        (otherGroup, otherIndex) =>
          otherIndex !== groupIndex &&
          otherGroup.some((otherEvent) =>
            areIntervalsOverlapping(
              {
                start: parseISO(event.startAt),
                end: parseISO(event.endAt),
              },
              {
                start: parseISO(otherEvent.startAt),
                end: parseISO(otherEvent.endAt),
              },
            ),
          ),
      );

      if (!hasOverlap) style = { ...style, width: '100%', left: '0%' };

      return (
        <div key={event.id} className="absolute p-1" style={style}>
          <EventBlock event={event} />
        </div>
      );
    }),
  );
}
