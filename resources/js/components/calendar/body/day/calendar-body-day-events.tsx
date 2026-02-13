import { isSameDay } from 'date-fns';
import { useCalendarContext } from '../../calendar-context';

export default function CalendarBodyDayEvents() {
  const { events, date, setManageEventDialogOpen, setSelectedEvent } = useCalendarContext();
  const dayEvents = events.filter((event) => isSameDay(event.startAt, date));

  return dayEvents.length ? (
    <div className="flex flex-col gap-2">
      <p className="font-heading p-2 pb-0 font-medium">Events</p>
      <div className="flex flex-col gap-2">
        {dayEvents.map((event) => (
          <div
            key={event.id}
            className="flex cursor-pointer items-center gap-2 px-2"
            onClick={() => {
              setSelectedEvent(event);
              setManageEventDialogOpen(true);
            }}
          >
            <div className="flex items-center gap-2">
              <div className={`size-2 rounded-full bg-${event.color}-500`} />
              <p className="text-muted-foreground text-sm font-medium">{event.title}</p>
            </div>
          </div>
        ))}
      </div>
    </div>
  ) : (
    <div className="text-muted-foreground p-2">No events today...</div>
  );
}
