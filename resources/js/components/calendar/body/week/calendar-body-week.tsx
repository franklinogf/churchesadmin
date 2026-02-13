import { addDays, startOfWeek } from 'date-fns';
import { useCalendarContext } from '../../calendar-context';
import CalendarBodyDayContent from '../day/calendar-body-day-content';
import CalendarBodyMarginDayMargin from '../day/calendar-body-margin-day-margin';
export default function CalendarBodyWeek() {
  const { date } = useCalendarContext();

  const weekStart = startOfWeek(date, { weekStartsOn: 1 });
  const weekDays = Array.from({ length: 7 }, (_, i) => addDays(weekStart, i));

  return (
    <div className="flex grow divide-x overflow-hidden">
      <div className="flex grow flex-col divide-y overflow-hidden">
        <div className="flex flex-1 flex-col overflow-y-auto">
          <div className="relative flex flex-1 flex-col divide-x md:flex-row">
            <CalendarBodyMarginDayMargin className="hidden md:block" />
            {weekDays.map((day) => (
              <div key={day.toISOString()} className="flex flex-1 divide-x md:divide-x-0">
                <CalendarBodyMarginDayMargin className="block md:hidden" />
                <CalendarBodyDayContent date={day} />
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
