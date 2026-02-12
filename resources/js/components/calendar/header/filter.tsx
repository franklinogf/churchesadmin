import { useCalendar } from '@/components/calendar/contexts/calendar-context';
import type { TEventColor } from '@/components/calendar/types';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Separator } from '@/components/ui/separator';
import { Toggle } from '@/components/ui/toggle';
import { CheckIcon, Filter, RefreshCcw } from 'lucide-react';

export default function FilterEvents() {
  const { selectedColors, filterEventsBySelectedColors, clearFilter } = useCalendar();

  const colors: TEventColor[] = ['blue', 'green', 'red', 'yellow', 'purple', 'orange'];

  return (
    <DropdownMenu>
      <DropdownMenuTrigger asChild>
        <Toggle variant="outline" className="w-fit cursor-pointer">
          <Filter className="h-4 w-4" />
        </Toggle>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="end" className="w-37.5">
        {colors.map((color) => (
          <DropdownMenuItem
            key={color}
            className="flex cursor-pointer items-center gap-2"
            onClick={(e) => {
              e.preventDefault();
              filterEventsBySelectedColors(color);
            }}
          >
            <div className={`size-3.5 rounded-full bg-${color}-600 dark:bg-${color}-700`} />
            <span className="flex items-center justify-center gap-2 capitalize">
              {color}
              <span>
                {selectedColors.includes(color) && (
                  <span className="text-blue-500">
                    <CheckIcon className="size-4" />
                  </span>
                )}
              </span>
            </span>
          </DropdownMenuItem>
        ))}
        <Separator className="my-2" />
        <DropdownMenuItem
          disabled={selectedColors.length === 0}
          className="flex cursor-pointer gap-2"
          onClick={(e) => {
            e.preventDefault();
            clearFilter();
          }}
        >
          <RefreshCcw className="size-3.5" />
          Clear Filter
        </DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenu>
  );
}
