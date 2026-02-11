import { selectionHeader } from '@/components/custom-ui/datatable/columns';
import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { InputField } from '@/components/forms/inputs/InputField';
import { TextareaField } from '@/components/forms/inputs/TextareaField';
import { Button } from '@/components/ui/button';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import type { CalendarEvent } from '@/types/models/calendar-event';
import type { Member } from '@/types/models/member';
import { useForm } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { useMemo, useState } from 'react';

interface Props {
  events: CalendarEvent[];
  members: Member[];
}

export default function CalendarEventEmail({ events, members }: Props) {
  const [selectedMembers, setSelectedMembers] = useState<string[]>([]);
  const [selectedEvents, setSelectedEvents] = useState<string[]>([]);
  const { t } = useTranslations();

  const { data, setData, post, processing, errors } = useForm({
    member_ids: [] as string[],
    event_ids: [] as string[],
    subject: '',
    message: '',
  });

  const memberColumns: ColumnDef<Member>[] = useMemo(
    () => [
      selectionHeader as ColumnDef<Member>,
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Name')} />,
        accessorKey: 'name',
        enableHiding: false,
        enableColumnFilter: false,
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Last name')} />,
        accessorKey: 'lastName',
        enableHiding: false,
        enableColumnFilter: false,
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Email')} />,
        accessorKey: 'email',
        enableHiding: false,
        enableColumnFilter: false,
      },
    ],
    [t],
  );

  const eventColumns: ColumnDef<CalendarEvent>[] = useMemo(
    () => [
      selectionHeader as ColumnDef<CalendarEvent>,
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Title')} />,
        accessorKey: 'title',
        enableHiding: false,
        enableColumnFilter: false,
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Location')} />,
        accessorKey: 'location',
        enableHiding: false,
        enableColumnFilter: false,
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Start date')} />,
        accessorKey: 'startAt',
        enableHiding: false,
        enableColumnFilter: false,
        cell: ({ row }) => new Date(row.original.startAt).toLocaleString(),
      },
    ],
    [t],
  );

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault();

    post(route('calendar-events.email.store'), {
      onSuccess: () => {
        setSelectedMembers([]);
        setSelectedEvents([]);
        setData({
          member_ids: [],
          event_ids: [],
          subject: '',
          message: '',
        });
      },
    });
  }

  return (
    <AppLayout
      title={t('Send :name', { name: t('Calendar schedule') })}
      breadcrumbs={[
        { title: t('Communication') },
        { title: t('Calendar'), href: route('calendar-events.index') },
        { title: t('Send :name', { name: t('Schedule') }) },
      ]}
    >
      <div className="mx-auto w-full max-w-4xl space-y-6">
        <div className="bg-card rounded-lg border p-6">
          <h2 className="mb-4 text-lg font-semibold">{t('Select :name', { name: t('Members') })}</h2>
          <DataTable
            onSelectedRowsChange={(ids) => {
              setSelectedMembers(ids);
              setData('member_ids', ids);
            }}
            data={members}
            rowId="id"
            columns={memberColumns}
          />
        </div>

        <div className="bg-card rounded-lg border p-6">
          <h2 className="mb-4 text-lg font-semibold">{t('Select :name', { name: t('Calendar events') })}</h2>
          <DataTable
            onSelectedRowsChange={(ids) => {
              setSelectedEvents(ids);
              setData('event_ids', ids);
            }}
            data={events}
            rowId="id"
            columns={eventColumns}
          />
        </div>

        <form onSubmit={handleSubmit} className="bg-card rounded-lg border p-6">
          <h2 className="mb-4 text-lg font-semibold">{t('Email details')}</h2>

          <div className="space-y-4">
            <InputField label={t('Subject')} value={data.subject} onChange={(value) => setData('subject', value)} error={errors.subject} required />

            <TextareaField
              label={t('Custom message')}
              value={data.message}
              onChange={(value) => setData('message', value)}
              error={errors.message}
              placeholder={t('Optional message to include in the email')}
              rows={5}
            />

            <div className="flex justify-end">
              <Button type="submit" disabled={processing || selectedMembers.length === 0 || selectedEvents.length === 0}>
                {t('Send :name', { name: t('Email') })}
              </Button>
            </div>

            {selectedMembers.length === 0 && selectedEvents.length === 0 && (
              <p className="text-muted-foreground text-sm">{t('Please select at least one member and one event')}</p>
            )}
            {selectedMembers.length === 0 && selectedEvents.length > 0 && (
              <p className="text-muted-foreground text-sm">{t('Please select at least one member')}</p>
            )}
            {selectedMembers.length > 0 && selectedEvents.length === 0 && (
              <p className="text-muted-foreground text-sm">{t('Please select at least one event')}</p>
            )}
          </div>
        </form>
      </div>
    </AppLayout>
  );
}
