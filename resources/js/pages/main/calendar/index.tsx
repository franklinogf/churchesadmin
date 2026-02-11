import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { TenantPermission } from '@/enums/TenantPermission';
import { useTranslations } from '@/hooks/use-translations';
import { useUser } from '@/hooks/use-user';
import AppLayout from '@/layouts/app-layout';
import {
  formatDayHeader,
  formatMonthYear,
  formatWeekRange,
  getNextDay,
  getNextMonth,
  getNextWeek,
  getPreviousDay,
  getPreviousMonth,
  getPreviousWeek,
} from '@/lib/calendar-utils';
import type { CalendarEvent } from '@/types/models/calendar-event';
import { router } from '@inertiajs/react';
import { ChevronLeftIcon, ChevronRightIcon, PlusIcon } from 'lucide-react';
import { useState } from 'react';
import { CalendarDayView } from './components/calendar-day-view';
import { CalendarEventFormDialog } from './components/calendar-event-form-dialog';
import { CalendarMonthView } from './components/calendar-month-view';
import { CalendarWeekView } from './components/calendar-week-view';

interface CalendarEventsIndexProps {
  events: CalendarEvent[];
}

type ViewMode = 'month' | 'week' | 'day';

export default function CalendarEventsIndex({ events }: CalendarEventsIndexProps) {
  const { t } = useTranslations();
  const { can: userCan } = useUser();
  const [currentDate, setCurrentDate] = useState(new Date());
  const [viewMode, setViewMode] = useState<ViewMode>('month');
  const [selectedEvent, setSelectedEvent] = useState<CalendarEvent | undefined>();
  const [openModal, setOpenModal] = useState(false);
  const [modalMode, setModalMode] = useState<'create' | 'edit'>('create');
  const [prefilledDate, setPrefilledDate] = useState<Date | undefined>();

  // Navigation handlers
  const handlePrevious = () => {
    if (viewMode === 'month') setCurrentDate(getPreviousMonth(currentDate));
    else if (viewMode === 'week') setCurrentDate(getPreviousWeek(currentDate));
    else setCurrentDate(getPreviousDay(currentDate));
  };

  const handleNext = () => {
    if (viewMode === 'month') setCurrentDate(getNextMonth(currentDate));
    else if (viewMode === 'week') setCurrentDate(getNextWeek(currentDate));
    else setCurrentDate(getNextDay(currentDate));
  };

  const handleToday = () => {
    setCurrentDate(new Date());
  };

  // Event handlers
  const handleDateClick = (date: Date) => {
    setPrefilledDate(date);
    setSelectedEvent(undefined);
    setModalMode('create');
    setOpenModal(true);
  };

  const handleWeekDayClick = (date: Date) => {
    setCurrentDate(date);
    setViewMode('day');
  };

  const handleTimeSlotClick = (date: Date, hour: number) => {
    const selectedDateTime = new Date(date);
    selectedDateTime.setHours(hour, 0, 0, 0);
    setPrefilledDate(selectedDateTime);
    setSelectedEvent(undefined);
    setModalMode('create');
    setOpenModal(true);
  };

  const handleEventClick = (event: CalendarEvent) => {
    setSelectedEvent(event);
    setPrefilledDate(undefined);
    setModalMode('edit');
    setOpenModal(true);
  };

  const handleDelete = (event: CalendarEvent) => {
    router.delete(route('calendar-events.destroy', event.id));
  };

  // Get header text based on view mode
  const getHeaderText = () => {
    if (viewMode === 'month') return formatMonthYear(currentDate);
    if (viewMode === 'week') return formatWeekRange(currentDate);
    return formatDayHeader(currentDate);
  };

  return (
    <AppLayout title={t('Calendar')} breadcrumbs={[{ title: t('Calendar') }]}>
      <PageTitle description={t('Manage events and activities')}>{t('Calendar')}</PageTitle>

      {/* Calendar event dialog */}
      <CalendarEventFormDialog
        mode={modalMode}
        event={selectedEvent}
        open={openModal}
        setOpen={setOpenModal}
        prefilledDate={prefilledDate}
        onDelete={handleDelete}
      />

      {/* Toolbar */}
      <div className="mb-4 flex flex-wrap items-center justify-between gap-4">
        {/* Navigation */}
        <div className="flex items-center gap-2">
          <Button variant="outline" size="sm" onClick={handlePrevious}>
            <ChevronLeftIcon className="size-4" />
          </Button>
          <Button variant="outline" size="sm" onClick={handleToday}>
            {t('Today')}
          </Button>
          <Button variant="outline" size="sm" onClick={handleNext}>
            <ChevronRightIcon className="size-4" />
          </Button>
          <div className="ml-4 text-lg font-semibold">{getHeaderText()}</div>
        </div>

        {/* Actions */}
        <div className="flex items-center gap-2">
          {userCan(TenantPermission.CALENDAR_EVENTS_CREATE) && (
            <Button size="sm" onClick={() => handleDateClick(currentDate)}>
              <PlusIcon className="mr-1 size-4" />
              {t('Add :model', { model: t('Event') })}
            </Button>
          )}

          {/* {userCan(TenantPermission.CALENDAR_EVENTS_EXPORT) && (
            <Button variant="outline" size="sm" onClick={() => router.get(route('calendar-events.pdf.index'))}>
              <PrinterIcon className="mr-1 size-4" />
              {t('Export')}
            </Button>
          )} */}

          {/* {userCan(TenantPermission.CALENDAR_EVENTS_EMAIL) && (
            <Button variant="outline" size="sm" onClick={() => router.get(route('calendar-events.email.index'))}>
              <MailIcon className="mr-1 size-4" />
              {t('Send')}
            </Button>
          )} */}
        </div>
      </div>

      {/* View tabs and content */}
      <Tabs value={viewMode} onValueChange={(value) => setViewMode(value as ViewMode)}>
        <TabsList>
          <TabsTrigger value="month">{t('Month')}</TabsTrigger>
          <TabsTrigger value="week">{t('Week')}</TabsTrigger>
          <TabsTrigger value="day">{t('Day')}</TabsTrigger>
        </TabsList>

        <TabsContent value="month" className="mt-4">
          <CalendarMonthView currentDate={currentDate} events={events} onDateClick={handleDateClick} onEventClick={handleEventClick} />
        </TabsContent>

        <TabsContent value="week" className="mt-4">
          <CalendarWeekView
            onHeaderClick={handleWeekDayClick}
            currentDate={currentDate}
            events={events}
            onTimeSlotClick={handleTimeSlotClick}
            onEventClick={handleEventClick}
          />
        </TabsContent>

        <TabsContent value="day" className="mt-4">
          <CalendarDayView currentDate={currentDate} events={events} onTimeSlotClick={handleTimeSlotClick} onEventClick={handleEventClick} />
        </TabsContent>
      </Tabs>
    </AppLayout>
  );
}
