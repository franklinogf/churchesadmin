import VisitController from '@/actions/App/Http/Controllers/VisitController';
import VisitFollowUpController from '@/actions/App/Http/Controllers/VisitFollowUpController';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/datatable-actions-dropdown';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import Datatable from '@/components/datatable/datatable';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { FollowUpForm } from '@/components/forms/follow-up-form';
import { PageTitle } from '@/components/PageTitle';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import AppLayout from '@/layouts/app-layout';
import useConfirmationStore from '@/stores/confirmation-store';
import type { SelectOption } from '@/types';
import type { MakeRequired } from '@/types/generics';
import type { Visit, VisitFollowUp } from '@/types/models/visit';
import { router } from '@inertiajs/react';
import type { ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, Trash2Icon } from 'lucide-react';
import { useMemo, useState } from 'react';
import { useTranslation } from 'react-i18next';

interface VisitsIndexProps {
  visit: MakeRequired<Visit, 'followUps'>;
  memberOptions: SelectOption[];
  followUpTypeOptions: SelectOption[];
}

export default function VisitsIndex({ visit, memberOptions, followUpTypeOptions }: VisitsIndexProps) {
  const { t: tEnum } = useTranslation('enum');
  const { t: tPages } = useTranslation('pages');
  const [open, setOpen] = useState(false);

  const columns: ColumnDef<VisitFollowUp>[] = useMemo(
    () => [
      {
        enableHiding: false,
        header: ({ column }) => <DatatableHeader column={column} title="Member" />,
        accessorKey: 'member.name',
        cell: ({ row }) => (
          <span>
            {row.original.member?.name} {row.original.member?.lastName}
          </span>
        ),
      },
      {
        enableHiding: false,
        header: ({ column }) => <DatatableHeader column={column} title="Type" />,
        accessorKey: 'type',
        cell: ({ row }) => (
          <DatatableCell justify="center">
            <Badge>{tEnum(($) => $.followUpType[row.original.type as keyof typeof $.followUpType])}</Badge>
          </DatatableCell>
        ),
      },
      {
        enableHiding: false,
        header: ({ column }) => <DatatableHeader column={column} title="Date" />,
        accessorKey: 'followUpAt',
        cell: ({ row }) => (
          <DatatableCell justify="center">
            <span>{row.original.followUpAt}</span>
          </DatatableCell>
        ),
      },
      {
        header: ({ column }) => <DatatableHeader column={column} title="Notes" />,
        accessorKey: 'notes',
        cell: ({ row }) => {
          if (!row.original.notes) {
            return <DatatableCell justify="center">{tPages(($) => $.main.visits.followUps.index.noNotes)}</DatatableCell>;
          }
          return (
            <DatatableCell justify="center">
              <Dialog>
                <DialogTrigger asChild>
                  <span className="line-clamp-2 cursor-pointer text-wrap hover:underline">{row.original.notes}</span>
                </DialogTrigger>
                <DialogContent>
                  <DialogHeader>
                    <DialogTitle>{tPages(($) => $.main.visits.followUps.index.followUpNotes)}</DialogTitle>
                    <DialogDescription hidden />
                  </DialogHeader>
                  <span>{row.original.notes}</span>
                </DialogContent>
              </Dialog>
            </DatatableCell>
          );
        },
      },
      {
        id: 'actions',
        enableHiding: false,
        size: 0,
        cell: function CellComponent({ row }) {
          const [open, setOpen] = useState(false);
          const { openConfirmation } = useConfirmationStore();
          return (
            <>
              <FollowUpForm
                followUp={row.original}
                membersOptions={memberOptions}
                followUpTypeOptions={followUpTypeOptions}
                visit={visit}
                open={open}
                setOpen={setOpen}
              />

              <DatatableActionsDropdown>
                <DropdownMenuItem onSelect={() => setOpen(true)}>
                  <Edit2Icon className="size-4" />
                  {tPages(($) => $.main.visits.followUps.index.edit)}
                </DropdownMenuItem>
                <DropdownMenuItem
                  variant="destructive"
                  onSelect={() => {
                    openConfirmation({
                      title: tPages(($) => $.main.visits.followUps.index.areYouSureYouWantToDeleteThisModel, {
                        model: tPages(($) => $.main.visits.followUps.index.followUp),
                      }),
                      description: tPages(($) => $.main.visits.followUps.index.thisActionCannotBeUndone),
                      actionLabel: tPages(($) => $.main.visits.followUps.index.delete),
                      actionVariant: 'destructive',
                      cancelLabel: tPages(($) => $.main.visits.followUps.index.cancel),
                      onAction: () => {
                        router.visit(VisitFollowUpController.destroy(row.original.id), {
                          preserveState: true,
                          preserveScroll: true,
                        });
                      },
                    });
                  }}
                >
                  <Trash2Icon className="size-4" />
                  {tPages(($) => $.main.visits.followUps.index.delete)}
                </DropdownMenuItem>
              </DatatableActionsDropdown>
            </>
          );
        },
      },
    ],
    [tEnum, tPages, memberOptions, followUpTypeOptions, visit],
  );

  return (
    <AppLayout
      title={tPages(($) => $.main.visits.followUps.index.nameFollowUps, { name: `${visit.name} ${visit.lastName}` })}
      breadcrumbs={[
        { title: tPages(($) => $.main.visits.followUps.index.visits), href: VisitController.index().url },
        { title: `${visit.name} ${visit.lastName}` },
        { title: tPages(($) => $.main.visits.followUps.index.followUps) },
      ]}
    >
      <PageTitle>{tPages(($) => $.main.visits.followUps.index.nameFollowUps, { name: `${visit.name} ${visit.lastName}` })}</PageTitle>
      <FollowUpForm membersOptions={memberOptions} followUpTypeOptions={followUpTypeOptions} visit={visit} open={open} setOpen={setOpen} />

      <div className="mx-auto w-full max-w-2xl">
        <Datatable
          renderLeftTop={
            <Button size="sm" onClick={() => setOpen(true)}>
              {tPages(($) => $.main.visits.followUps.index.addModel, { model: tPages(($) => $.main.visits.followUps.index.followUp) })}
            </Button>
          }
          data={visit.followUps}
          columns={columns}
        />
      </div>
    </AppLayout>
  );
}
