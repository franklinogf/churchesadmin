import { format, isSameDay } from 'date-fns';
import { cn } from '../../../lib/utils';

export default function CalendarBodyHeader({ date, onlyDay = false }: { date: Date; onlyDay?: boolean }) {
  const isToday = isSameDay(date, new Date());

  return (
    <div className="bg-background sticky top-0 z-10 flex w-full items-center justify-center gap-1 border-b py-2">
      <span className={cn('text-xs font-medium', isToday ? 'text-primary' : 'text-muted-foreground')}>{format(date, 'EEE')}</span>
      {!onlyDay && <span className={cn('text-xs font-medium', isToday ? 'text-primary font-bold' : 'text-foreground')}>{format(date, 'dd')}</span>}
    </div>
  );
}
