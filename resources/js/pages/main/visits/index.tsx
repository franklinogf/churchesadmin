import MemberController from '@/actions/App/Http/Controllers/MemberController';
import VisitController from '@/actions/App/Http/Controllers/VisitController';
import VisitFollowUpController from '@/actions/App/Http/Controllers/VisitFollowUpController';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/datatable-actions-dropdown';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import Datatable from '@/components/datatable/datatable';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { sortDate } from '@/components/datatable/sorting-functions';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import AppLayout from '@/layouts/app-layout';
import type { Visit } from '@/types/models/visit';
import { Link } from '@inertiajs/react';
import type { ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, FileEditIcon, UserPlusIcon } from 'lucide-react';
import { useMemo } from 'react';
import { useTranslation } from 'react-i18next';

interface VisitsIndexProps {
  visits: Visit[];
}

export default function VisitsIndex({ visits }: VisitsIndexProps) {
  const { t: tPages } = useTranslation('pages');
  const columns = useMemo<ColumnDef<Visit>[]>(
    () => [
      {
        enableHiding: false,
        header: ({ column }) => <DatatableHeader column={column} title="Name" />,
        accessorKey: 'name',
        cell: ({ row }) => (
          <span>
            {row.original.name} {row.original.lastName}
          </span>
        ),
      },
      {
        enableHiding: false,
        header: ({ column }) => <DatatableHeader justify="center" column={column} title="Phone" />,
        accessorKey: 'phone',
        cell: ({ row }) => <DatatableCell justify="center">{row.original.phone}</DatatableCell>,
      },
      {
        enableHiding: false,
        header: ({ column }) => <DatatableHeader justify="center" column={column} title="Email" />,
        accessorKey: 'email',
        cell: ({ row }) => <DatatableCell justify="center">{row.original.email}</DatatableCell>,
      },
      {
        sortingFn: sortDate,
        enableHiding: false,
        header: ({ column }) => <DatatableHeader justify="center" column={column} title="First visit" />,
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
                <Link href={VisitController.edit(row.original.id).url} className="w-full">
                  <Edit2Icon className="size-4" />
                  {tPages(($) => $.main.visits.index.edit)}
                </Link>
              </DropdownMenuItem>
              <DropdownMenuItem asChild>
                <Link href={VisitFollowUpController.index(row.original.id).url} className="w-full">
                  <FileEditIcon className="size-4" />
                  {tPages(($) => $.main.visits.index.followUps)}
                </Link>
              </DropdownMenuItem>
              <DropdownMenuItem asChild>
                <Link href={MemberController.create({ query: { visit: row.original.id } })} className="w-full">
                  <UserPlusIcon className="size-4" />
                  {tPages(($) => $.main.visits.index.transferToMember)}
                </Link>
              </DropdownMenuItem>
            </DatatableActionsDropdown>
          );
        },
      },
    ],
    [tPages],
  );

  return (
    <AppLayout title={tPages(($) => $.main.visits.index.visitors)} breadcrumbs={[{ title: tPages(($) => $.main.visits.index.visitors) }]}>
      <PageTitle description={tPages(($) => $.main.visits.index.manageTheVisitsThatComesToTheChurch)}>
        {tPages(($) => $.main.visits.index.visitors)}
      </PageTitle>

      <Datatable
        renderLeftTop={
          <Button asChild size="sm">
            <Link href={VisitController.create()}>
              {tPages(($) => $.main.visits.index.addModel, { model: tPages(($) => $.main.visits.index.visit) })}
            </Link>
          </Button>
        }
        data={visits}
        columns={columns}
      />
    </AppLayout>
  );
}
