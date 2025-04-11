import { format, Locale, parse } from 'date-fns';

/* ---------------------------- Dates formattters --------------------------- */

export function formatDateToString(date = new Date()) {
  return format(date, 'yyyy-MM-dd');
}

export function formatStringToDate(date = formatDateToString(new Date())) {
  if (!date) return '';
  return parse(date, 'yyyy-MM-dd', new Date());
}

/* ----------------------------- Time formatters ---------------------------- */
export function formatTime(time: string) {
  if (!time) return undefined;
  const [hours, minutes, seconds] = time.split(':');
  const timeToformat = new Date(0, 0, 0, Number(hours) || 0, Number(minutes) || 0, Number(seconds) || 0);
  return new Intl.DateTimeFormat('es', {
    hour12: false,
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
  }).format(timeToformat);
}

export function formatStringToTime(time?: string) {
  if (!time) return undefined;
  const [hours, minutes, seconds] = time.split(':');
  const newTime = new Date(0, 0, 0, Number(hours) || 0, Number(minutes) || 0, Number(seconds) || 0);
  return newTime;
}

export function formatTimeToString(time?: Date) {
  if (!time) return undefined;
  return new Intl.DateTimeFormat('es', {
    hour12: false,
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
  }).format(new Date(time));
}

export function genMonths({ locale }: { locale: Pick<Locale, 'options' | 'localize' | 'formatLong'> }) {
  return Array.from({ length: 12 }, function (_, i) {
    const month = format(new Date(new Date().getFullYear(), i), 'MMM', { locale }).toLowerCase();
    const formattedMonth = month.charAt(0).toUpperCase() + month.slice(1);
    return {
      value: i,
      label: formattedMonth,
    };
  });
}

export function genDays({
  locale,
  monthIndex,
  year,
}: {
  monthIndex: number;
  year: number;
  locale: Pick<Locale, 'options' | 'localize' | 'formatLong'>;
}) {
  return Array.from({ length: new Date(year, monthIndex + 1, 0).getDate() }, function (_, i) {
    const day = format(new Date(year, monthIndex, i + 1), 'eeee', { locale }).toLowerCase();
    const formattedDay = day.charAt(0).toUpperCase() + day.slice(1);
    return {
      value: i + 1,
      label: formattedDay,
    };
  });
}

export function genYears({ startYear, endYear }: { startYear: number; endYear: number }) {
  return Array.from({ length: endYear - startYear + 1 }, (_, i) => startYear + i);
}
