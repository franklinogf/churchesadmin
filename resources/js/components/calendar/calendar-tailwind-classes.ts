// this is used to generate all tailwind classes for the calendar

import { CalendarEventColorEnum } from '@/enums/CalendarEventColorEnum';

// if you want to use your own colors, you can override the classes here
interface ColorOption {
  value: CalendarEventColorEnum;
  label: string;
  class: {
    picker: string;
    base: string;
    light: string;
    dark: string;
  };
}
export const colorOptions: ColorOption[] = [
  {
    value: CalendarEventColorEnum.BLUE,
    label: 'Blue',
    class: {
      picker: 'bg-blue-500!',
      base: 'bg-blue-500 border-blue-500 bg-blue-500/10 hover:bg-blue-500/20 text-blue-500',
      light: 'bg-blue-300 border-blue-300 bg-blue-300/10 text-blue-300',
      dark: 'dark:bg-blue-700 dark:border-blue-700 bg-blue-700/10 text-blue-700',
    },
  },
  {
    value: CalendarEventColorEnum.GREEN,
    label: 'Green',
    class: {
      picker: 'bg-green-500!',
      base: 'bg-green-500 border-green-500 bg-green-500/10 hover:bg-green-500/20 text-green-500',
      light: 'bg-green-300 border-green-300 bg-green-300/10 text-green-300',
      dark: 'dark:bg-green-700 dark:border-green-700 bg-green-700/10 text-green-700',
    },
  },
  {
    value: CalendarEventColorEnum.ORANGE,
    label: 'Orange',
    class: {
      picker: 'bg-orange-500!',
      base: 'bg-orange-500 border-orange-500 bg-orange-500/10 hover:bg-orange-500/20 text-orange-500',
      light: 'bg-orange-300 border-orange-300 bg-orange-300/10 text-orange-300',
      dark: 'dark:bg-orange-700 dark:border-orange-700 bg-orange-700/10 text-orange-700',
    },
  },
  {
    value: CalendarEventColorEnum.PURPLE,
    label: 'Purple',
    class: {
      picker: 'bg-purple-500!',
      base: 'bg-purple-500 border-purple-500 bg-purple-500/10 hover:bg-purple-500/20 text-purple-500',
      light: 'bg-purple-300 border-purple-300 bg-purple-300/10 text-purple-300',
      dark: 'dark:bg-purple-700 dark:border-purple-700 bg-purple-700/10 text-purple-700',
    },
  },
  {
    value: CalendarEventColorEnum.RED,
    label: 'Red',
    class: {
      picker: 'bg-red-500!',
      base: 'bg-red-500 border-red-500 bg-red-500/10 hover:bg-red-500/20 text-red-500',
      light: 'bg-red-300 border-red-300 bg-red-300/10 text-red-300',
      dark: 'dark:bg-red-700 dark:border-red-700 bg-red-700/10 text-red-700',
    },
  },
  {
    value: CalendarEventColorEnum.YELLOW,
    label: 'Yellow',
    class: {
      picker: 'bg-yellow-500!',
      base: 'bg-yellow-500 border-yellow-500 bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-500',
      light: 'bg-yellow-300 border-yellow-300 bg-yellow-300/10 text-yellow-300',
      dark: 'dark:bg-yellow-700 dark:border-yellow-700 bg-yellow-700/10 text-yellow-700',
    },
  },
];
