import { ScrollArea } from '@/components/ui/scroll-area';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { genDays, genMonths, genYears } from '@/lib/datetime';
import { enUS } from 'date-fns/locale';
import { useEffect, useState } from 'react';

export interface DatePickerProps {
    startYear: number;
    endYear: number;
    selected: Date;
    disabled?: boolean;
    onSelect: (date: Date) => void;
}

export function DatePicker({ startYear, endYear, selected, disabled, onSelect }: DatePickerProps) {
    const [days, setDays] = useState(genDays({ locale: enUS, monthIndex: selected.getMonth(), year: selected.getFullYear() }));
    const [months, setMonths] = useState(genMonths({ locale: enUS }));

    const years = genYears({ startYear, endYear }).toSorted((a, b) => b - a);

    const handleDayChange = (day: string) => {
        const year = selected.getFullYear();
        const month = selected.getMonth();
        const newDate = new Date(year, month, parseInt(day));
        onSelect(newDate);
    };

    const handleMonthChange = (month: string) => {
        const year = selected.getFullYear();
        const day = selected.getDate();
        const monthIndex = months.find((m) => m.value.toString() === month)?.value || 0;
        const newDate = new Date(year, monthIndex, day);
        onSelect(newDate);
    };

    const handleYearChange = (year: string) => {
        const month = selected.getMonth();
        const day = selected.getDate();
        const newDate = new Date(parseInt(year), month, day);

        onSelect(newDate);
    };

    useEffect(() => {
        setDays(genDays({ locale: enUS, monthIndex: selected.getMonth(), year: selected.getFullYear() }));
        setMonths(genMonths({ locale: enUS }));
    }, [selected]);

    return (
        <div className="grid w-full max-w-[275px] grid-cols-3 gap-4">
            <Select disabled={disabled} onValueChange={handleDayChange}>
                <SelectTrigger className="h-auto max-h-10 shadow-sm focus:ring-0 focus:ring-offset-0 focus:outline-0 md:w-22">
                    <SelectValue
                        placeholder={
                            <div className="flex flex-col items-start">
                                <span className="text-muted-foreground text-[0.65rem] font-semibold uppercase select-none">Day</span>
                                <span className="font-normal">{selected.getDate() || '-'}</span>
                            </div>
                        }
                    >
                        <div className="flex flex-col items-start">
                            <span className="text-muted-foreground text-[0.65rem] font-semibold uppercase select-none">Day</span>
                            <span className="font-normal">{selected.getDate() || '-'}</span>
                        </div>
                    </SelectValue>
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
            <Select disabled={disabled} onValueChange={handleMonthChange}>
                <SelectTrigger className="h-auto max-h-10 shadow-sm focus:ring-0 focus:ring-offset-0 focus:outline-0 md:w-22">
                    <SelectValue
                        placeholder={
                            <div className="flex flex-col items-start">
                                <span className="text-muted-foreground text-[0.65rem] font-semibold uppercase select-none">Month</span>
                                <span className="font-normal">{months.find((m) => m.value === selected.getMonth())?.label || '-'}</span>
                            </div>
                        }
                    >
                        <div className="flex flex-col items-start">
                            <span className="text-muted-foreground text-[0.65rem] font-semibold uppercase select-none">Month</span>
                            <span className="font-normal">{months.find((m) => m.value === selected.getMonth())?.label || '-'}</span>
                        </div>
                    </SelectValue>
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
            <Select disabled={disabled} onValueChange={handleYearChange}>
                <SelectTrigger className="h-auto max-h-10 shadow-sm focus:ring-0 focus:ring-offset-0 focus:outline-0 md:w-22">
                    <SelectValue
                        placeholder={
                            <div className="flex flex-col items-start">
                                <span className="text-muted-foreground text-[0.65rem] font-semibold uppercase select-none">Year</span>
                                <span className="font-normal">{selected.getFullYear() || '-'}</span>
                            </div>
                        }
                    >
                        <div className="flex flex-col items-start">
                            <span className="text-muted-foreground text-[0.65rem] font-semibold uppercase select-none">Year</span>
                            <span className="font-normal">{selected.getFullYear() || '-'}</span>
                        </div>
                    </SelectValue>
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
