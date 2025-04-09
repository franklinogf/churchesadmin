import { ScrollArea } from '@/components/ui/scroll-area';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { genDays, genMonths, genYears } from '@/lib/datetime';
import { Locale } from 'date-fns/locale';
import { useEffect, useId, useState } from 'react';

export interface DatePickerProps {
    startYear: number;
    endYear: number;
    selected: Date | null;
    disabled?: boolean;
    locale: Locale;
    onSelect: (date: Date) => void;
}

export function DatePicker({ startYear, endYear, selected, disabled, locale, onSelect }: DatePickerProps) {
    const [days, setDays] = useState(genDays({ locale, monthIndex: selected?.getMonth() || 0, year: selected?.getFullYear() || 0 }));
    const [months, setMonths] = useState(genMonths({ locale }));

    const [selectedMonth, setSelectedMonth] = useState(selected?.getMonth().toString() || undefined);
    const [selectedDay, setSelectedDay] = useState(selected?.getDate().toString() || undefined);
    const [selectedYear, setSelectedYear] = useState(selected?.getFullYear().toString() || undefined);

    const monthId = useId();
    const dayId = useId();
    const yearId = useId();

    const years = genYears({ startYear, endYear }).toSorted((a, b) => b - a);

    const handleDayChange = (day: string) => {
        setSelectedDay(day);
    };

    const handleMonthChange = (month: string) => {
        setSelectedMonth(month);
    };

    const handleYearChange = (year: string) => {
        setSelectedYear(year);
    };

    useEffect(() => {
        if (!selectedMonth || !selectedYear) return;
        setDays(genDays({ locale, monthIndex: parseInt(selectedMonth), year: parseInt(selectedYear) }));
        setMonths(genMonths({ locale }));

        if (selectedMonth && selectedYear && selectedDay) {
            const newDate = new Date(parseInt(selectedYear), parseInt(selectedMonth), parseInt(selectedDay));
            onSelect(newDate);
        }
    }, [selectedDay, selectedMonth, selectedYear]);

    return (
        <div className="grid w-full max-w-[275px] grid-cols-3 gap-4">
            <Select name={monthId} disabled={disabled} value={selectedMonth} onValueChange={handleMonthChange}>
                <SelectTrigger className="h-auto max-h-10 shadow-sm focus:ring-0 focus:ring-offset-0 focus:outline-0 md:w-22">
                    <SelectValue
                        placeholder={
                            <div className="flex flex-col items-start">
                                <span className="text-muted-foreground text-[0.65rem] font-semibold uppercase select-none">Month</span>
                                <span className="font-normal">-</span>
                            </div>
                        }
                    />
                </SelectTrigger>
                <SelectContent>
                    <ScrollArea className="h-48">
                        {months.map((month, index) => (
                            <SelectItem key={index} value={month.value.toString()}>
                                {month.label}
                            </SelectItem>
                        ))}
                    </ScrollArea>
                </SelectContent>
            </Select>

            <Select name={dayId} disabled={disabled} value={selectedDay} onValueChange={handleDayChange}>
                <SelectTrigger className="h-auto max-h-10 shadow-sm focus:ring-0 focus:ring-offset-0 focus:outline-0 md:w-22">
                    <SelectValue
                        placeholder={
                            <div className="flex flex-col items-start">
                                <span className="text-muted-foreground text-[0.65rem] font-semibold uppercase select-none">Day</span>
                                <span className="font-normal">-</span>
                            </div>
                        }
                    />
                </SelectTrigger>
                <SelectContent>
                    <ScrollArea className="h-48">
                        {days.map((day) => (
                            <SelectItem key={day.value} value={day.value.toString()}>
                                {day.value}
                            </SelectItem>
                        ))}
                    </ScrollArea>
                </SelectContent>
            </Select>

            <Select name={yearId} value={selectedYear} disabled={disabled} onValueChange={handleYearChange}>
                <SelectTrigger className="h-auto max-h-10 shadow-sm focus:ring-0 focus:ring-offset-0 focus:outline-0 md:w-22">
                    <SelectValue
                        placeholder={
                            <div className="flex flex-col items-start">
                                <span className="text-muted-foreground text-[0.65rem] font-semibold uppercase select-none">Year</span>
                                <span className="font-normal">-</span>
                            </div>
                        }
                    />
                </SelectTrigger>
                <SelectContent>
                    <ScrollArea className="h-48">
                        {years.map((year) => (
                            <SelectItem key={year} value={year.toString()}>
                                {year}
                            </SelectItem>
                        ))}
                    </ScrollArea>
                </SelectContent>
            </Select>
        </div>
    );
}
