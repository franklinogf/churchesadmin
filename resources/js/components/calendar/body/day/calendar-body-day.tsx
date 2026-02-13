import { useCalendarContext } from '../../calendar-context';
import CalendarBodyDayCalendar from './calendar-body-day-calendar';
import CalendarBodyDayContent from './calendar-body-day-content';
import CalendarBodyDayEvents from './calendar-body-day-events';
import CalendarBodyMarginDayMargin from './calendar-body-margin-day-margin';

export default function CalendarBodyDay() {
  const { date } = useCalendarContext();
  return (
    <div className="flex grow divide-x overflow-hidden">
      <div className="flex grow flex-col divide-y overflow-hidden">
        <div className="flex flex-1 flex-col overflow-y-auto">
          <div className="relative flex flex-1 divide-x">
            <CalendarBodyMarginDayMargin />
            <CalendarBodyDayContent date={date} />
          </div>
        </div>
      </div>
      <div className="hidden max-w-69 grow flex-col divide-y lg:flex">
        <CalendarBodyDayCalendar />
        <CalendarBodyDayEvents />
      </div>
    </div>
  );
}
