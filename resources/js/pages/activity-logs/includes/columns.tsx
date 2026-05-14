import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { HoverCard, HoverCardContent, HoverCardTrigger } from '@/components/ui/hover-card';
import { cleanProperty } from '@/lib/utils';
import type { ActivityLog } from '@/types/models/activity-log';
import { type ColumnDef } from '@tanstack/react-table';
import { format } from 'date-fns';
import { EyeIcon } from 'lucide-react';
import { useTranslation } from 'react-i18next';

export const columns: ColumnDef<ActivityLog>[] = [
  {
    header: ({ column }) => <DatatableHeader column={column} title="Type" />,
    accessorKey: 'logName',
    enableHiding: false,
    cell: function CellComponent({
      row: {
        original: { logName },
      },
    }) {
      return (
        <DatatableCell>
          <Badge variant="secondary" className="capitalize">
            {logName.replace(/_/g, ' ')}
          </Badge>
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Event" />,
    accessorKey: 'event',
    enableHiding: false,
    cell: function CellComponent({
      row: {
        original: { event },
      },
    }) {
      return (
        <DatatableCell>
          <Badge variant="secondary" className="capitalize">
            {event}
          </Badge>
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="User" />,
    accessorKey: 'causer.name',
    enableHiding: false,
    filterFn: 'equals',
    meta: { filterVariant: 'select' },
    cell: function CellComponent({
      row: {
        original: { causer },
      },
    }) {
      return <DatatableCell className="text-sm font-medium">{causer.name}</DatatableCell>;
    },
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="IP" />,
    accessorKey: 'properties.extra.ip_address',
    enableHiding: false,
    filterFn: 'equals',
    meta: { filterVariant: 'select' },
    cell: function CellComponent({
      row: {
        original: { properties },
      },
    }) {
      return <DatatableCell className="text-sm font-medium">{properties?.extra.ip_address}</DatatableCell>;
    },
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Description" />,
    accessorKey: 'description',
    enableHiding: false,
    enableColumnFilter: false,
    cell: function CellComponent({
      row: {
        original: { description },
      },
    }) {
      return (
        <DatatableCell>
          <HoverCard>
            <HoverCardTrigger>
              <span className="max-w-xs truncate">{description}</span>
            </HoverCardTrigger>
            <HoverCardContent>
              <p>{description}</p>
            </HoverCardContent>
          </HoverCard>
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Changes" />,
    accessorKey: 'properties',
    enableHiding: false,
    enableColumnFilter: false,
    cell: function CellComponent({ row: { original } }) {
      const { t: tPages } = useTranslation('pages');
      const { properties, subjectId, subjectType } = original;
      if (!properties || Object.keys(properties).length === 0) {
        return <DatatableCell className="text-gray-400">{tPages(($) => $.activityLogs.includes.columns.noChanges)}</DatatableCell>;
      }

      return (
        <DatatableCell>
          <Dialog>
            <DialogTrigger asChild>
              <Button variant="outline" size="icon">
                <EyeIcon className="h-4 w-4" />
                <span className="sr-only">{tPages(($) => $.activityLogs.includes.columns.viewChanges)}</span>
              </Button>
            </DialogTrigger>
            <DialogContent className="max-w-2xl">
              <DialogHeader>
                <DialogTitle>{tPages(($) => $.activityLogs.includes.columns.changes)}</DialogTitle>
                <DialogDescription>
                  {tPages(($) => $.activityLogs.includes.columns.hereAreTheChangesMadeToTheSubjectType, {
                    subject_type: subjectType,
                    subject_id: subjectId.toString(),
                  })}
                </DialogDescription>
              </DialogHeader>
              <div className="space-y-4">
                {properties.old && (
                  <div>
                    <div className="font-medium">{tPages(($) => $.activityLogs.includes.columns.old)}</div>
                    <div className="prose">
                      <pre className="rounded p-2 wrap-break-word whitespace-pre-wrap">
                        {Object.entries(properties.old)
                          .map(([key, value]) => `${cleanProperty(key)}: ${value}`)
                          .join('\n')}
                      </pre>
                    </div>
                  </div>
                )}

                {properties.attributes && (
                  <div>
                    <div className="font-medium">{tPages(($) => $.activityLogs.includes.columns.new)}</div>
                    <div className="prose">
                      <pre className="rounded p-2 wrap-break-word whitespace-pre-wrap">
                        {Object.entries(properties.attributes)
                          .map(([key, value]) => `${cleanProperty(key)}: ${value}`)
                          .join('\n')}
                      </pre>
                    </div>
                  </div>
                )}
              </div>
            </DialogContent>
          </Dialog>
        </DatatableCell>
      );
    },
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Date" />,
    accessorKey: 'createdAt',
    enableHiding: false,
    enableColumnFilter: false,
    size: 120,
    cell: function CellComponent({
      row: {
        original: { createdAt },
      },
    }) {
      return (
        <DatatableCell>
          <div className="mr-2 text-sm">{format(new Date(createdAt), 'MMM d, yyyy')}</div>
          <div className="text-xs text-gray-500">{format(new Date(createdAt), 'h:mm a')}</div>
        </DatatableCell>
      );
    },
  },
];
