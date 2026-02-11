import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { DatatableActionsDropdown } from '@/components/custom-ui/datatable/data-table-actions-dropdown';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { FollowUpForm } from '@/components/forms/follow-up-form';
import { PageTitle } from '@/components/PageTitle';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import useConfirmationStore from '@/stores/confirmationStore';
import type { SelectOption } from '@/types';
import type { MakeRequired } from '@/types/generics';
import type { Visit, VisitFollowUp } from '@/types/models/visit';
import { router } from '@inertiajs/react';
import type { ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, Trash2Icon } from 'lucide-react';
import { useMemo, useState } from 'react';

interface VisitsIndexProps {
  visit: MakeRequired<Visit, 'followUps'>;
  memberOptions: SelectOption[];
  followUpTypeOptions: SelectOption[];
}

export default function VisitsIndex({ visit, memberOptions, followUpTypeOptions }: VisitsIndexProps) {
  const { t } = useTranslations();
  const [open, setOpen] = useState(false);

  const columns: ColumnDef<VisitFollowUp>[] = useMemo(
    () => [
      {
        enableHiding: false,
        header: ({ column }) => <DataTableColumnHeader column={column} title="Member" />,
        accessorKey: 'member.name',
        cell: ({ row }) => (
          <span>
            {row.original.member?.name} {row.original.member?.lastName}
          </span>
        ),
      },
      {
        enableHiding: false,
        header: ({ column }) => <DataTableColumnHeader column={column} title="Type" />,
        accessorKey: 'type',
        cell: ({ row }) => (
          <DatatableCell justify="center">
            <Badge>{t(`enum.follow_up_type.${row.original.type}`)}</Badge>
          </DatatableCell>
        ),
      },
      {
        enableHiding: false,
        header: ({ column }) => <DataTableColumnHeader column={column} title="Date" />,
        accessorKey: 'followUpAt',
        cell: ({ row }) => (
          <DatatableCell justify="center">
            <span>{row.original.followUpAt}</span>
          </DatatableCell>
        ),
      },
      {
        header: ({ column }) => <DataTableColumnHeader column={column} title="Notes" />,
        accessorKey: 'notes',
        cell: ({ row }) => {
          if (!row.original.notes) {
            return <DatatableCell justify="center">{t('No notes')}</DatatableCell>;
          }
          return (
            <DatatableCell justify="center">
              <Dialog>
                <DialogTrigger asChild>
                  <span className="line-clamp-2 cursor-pointer text-wrap hover:underline">{row.original.notes}</span>
                </DialogTrigger>
                <DialogContent>
                  <DialogHeader>
                    <DialogTitle>{t('Follow Up Notes')}</DialogTitle>
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
                  {t('Edit')}
                </DropdownMenuItem>
                <DropdownMenuItem
                  //   variant="destructive"
                  onSelect={() => {
                    openConfirmation({
                      title: t('Are you sure you want to delete this :model?', { model: t('Follow Up') }),
                      description: t('This action cannot be undone.'),
                      actionLabel: t('Delete'),
                      actionVariant: 'destructive',
                      cancelLabel: t('Cancel'),
                      onAction: () => {
                        router.delete(route('follow-ups.destroy', row.original.id), {
                          preserveState: true,
                          preserveScroll: true,
                        });
                      },
                    });
                  }}
                >
                  <Trash2Icon className="size-4" />
                  {t('Delete')}
                </DropdownMenuItem>
              </DatatableActionsDropdown>
            </>
          );
        },
      },
    ],
    [t, memberOptions, followUpTypeOptions, visit],
  );

  return (
    <AppLayout
      title={t(':name follow ups', { name: `${visit.name} ${visit.lastName}` })}
      breadcrumbs={[{ title: t('Visits'), href: route('visits.index') }, { title: `${visit.name} ${visit.lastName}` }, { title: t('Follow Ups') }]}
    >
      <PageTitle>{t(':name follow ups', { name: `${visit.name} ${visit.lastName}` })}</PageTitle>
      <FollowUpForm membersOptions={memberOptions} followUpTypeOptions={followUpTypeOptions} visit={visit} open={open} setOpen={setOpen} />

      <div className="mx-auto w-full max-w-2xl">
        <DataTable
          headerButton={
            <Button size="sm" onClick={() => setOpen(true)}>
              {t('Add :model', { model: t('Follow Up') })}
            </Button>
          }
          data={visit.followUps}
          columns={columns}
        />
      </div>
    </AppLayout>
  );
}
