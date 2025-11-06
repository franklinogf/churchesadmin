import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/data-table-actions-dropdown';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { sortDate } from '@/components/custom-ui/datatable/sorting-functions';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import type { Visit } from '@/types/models/visit';
import { Link } from '@inertiajs/react';
import type { ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, FileEditIcon, UserPlusIcon } from 'lucide-react';
import { useMemo } from 'react';

interface VisitsIndexProps {
  visits: Visit[];
}

export default function VisitsIndex({ visits }: VisitsIndexProps) {
  const { t } = useTranslations();

  const columns = useMemo<ColumnDef<Visit>[]>(
    () => [
      {
        enableHiding: false,
        header: ({ column }) => <DataTableColumnHeader column={column} title="Name" />,
        accessorKey: 'name',
        cell: ({ row }) => (
          <span>
            {row.original.name} {row.original.lastName}
          </span>
        ),
      },
      {
        enableHiding: false,
        header: ({ column }) => <DataTableColumnHeader justify="center" column={column} title="Phone" />,
        accessorKey: 'phone',
        cell: ({ row }) => <DatatableCell justify="center">{row.original.phone}</DatatableCell>,
      },
      {
        enableHiding: false,
        header: ({ column }) => <DataTableColumnHeader justify="center" column={column} title="Email" />,
        accessorKey: 'email',
        cell: ({ row }) => <DatatableCell justify="center">{row.original.email}</DatatableCell>,
      },
      {
        sortingFn: sortDate,
        enableHiding: false,
        header: ({ column }) => <DataTableColumnHeader justify="center" column={column} title="First visit" />,
        accessorKey: 'firstVisitDate',
        cell: ({ row }) => <DatatableCell justify="center">{row.original.firstVisitDate}</DatatableCell>,
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
                  {t('Follow Ups')}
                </Link>
              </DropdownMenuItem>
              <DropdownMenuItem asChild>
                <Link href={route('members.create', { visit: row.original.id })} className="w-full">
                  <UserPlusIcon className="size-4" />
                  {t('Transfer to member')}
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
    <AppLayout title={t('Visitors')} breadcrumbs={[{ title: t('Visitors') }]}>
      <PageTitle description={t('Manage the visits that comes to the church')}>{t('Visitors')}</PageTitle>

      <DataTable
        headerButton={
          <Button asChild size="sm">
            <Link href={route('visits.create')}>{t('Add :model', { model: t('Visit') })}</Link>
          </Button>
        }
        data={visits}
        columns={columns}
      />
    </AppLayout>
  );
}
