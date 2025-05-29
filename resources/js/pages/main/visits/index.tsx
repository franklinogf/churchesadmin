import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/data-table-actions-dropdown';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { PageTitle } from '@/components/PageTitle';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import type { Visit } from '@/types/models/visit';
import { Link } from '@inertiajs/react';
import type { ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, FileEditIcon } from 'lucide-react';
import { useMemo } from 'react';

interface VisitsIndexProps {
  visits: Visit[];
}

export default function VisitsIndex({ visits }: VisitsIndexProps) {
  const { t } = useTranslations();

  const columns: ColumnDef<Visit>[] = useMemo(
    () => [
      {
        enableHiding: false,
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Name')} />,
        accessorKey: 'name',
        cell: ({ row }) => (
          <span>
            {row.original.name} {row.original.lastName}
          </span>
        ),
      },
      {
        enableHiding: false,
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Phone')} />,
        accessorKey: 'phone',
        cell: ({ row }) => <span>{row.original.phone}</span>,
      },
      {
        enableHiding: false,
        header: ({ column }) => <DataTableColumnHeader column={column} title={t('Email')} />,
        accessorKey: 'email',
        cell: ({ row }) => <span>{row.original.email}</span>,
      },
      {
        id: 'actions',
        enableHiding: false,
        size: 0,
        cell: ({ row }) => {
          return (
            <DatatableActionsDropdown>
              <DropdownMenuItem asChild>
                <Link href={route('visits.edit', row.original.id)} className="w-full">
                  <Edit2Icon className="size-4" />
                  {t('Edit')}
                </Link>
              </DropdownMenuItem>
              <DropdownMenuItem asChild>
                <Link href={route('visits.follow-ups.index', row.original.id)} className="w-full">
                  <FileEditIcon className="size-4" />
                  {t('Follow ups')}
                </Link>
              </DropdownMenuItem>
            </DatatableActionsDropdown>
          );
        },
      },
    ],
    [t],
  );

  return (
    <AppLayout title={t('Visits')} breadcrumbs={[{ title: t('Visits') }]}>
      <PageTitle description={t('Manage the visits that comes to the church')}>{t('Visits')}</PageTitle>

      <DataTable data={visits} columns={columns} />
    </AppLayout>
  );
}
