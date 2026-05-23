import NegativaController from '@/actions/App/Http/Controllers/NegativaController';
import NegativaPdfController from '@/actions/App/Http/Controllers/Pdf/NegativaPdfController';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import useConfirmationStore from '@/stores/confirmation-store';
import type { Negativa } from '@/types/models/negativa';
import { Link, router } from '@inertiajs/react';
import type { ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, MoreHorizontalIcon, PrinterIcon, Trash2Icon } from 'lucide-react';
import { useTranslation } from 'react-i18next';

export const columns: ColumnDef<Negativa>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader column={column} title="Persona" />,
    accessorKey: 'personName',
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Desde" />,
    accessorKey: 'searchedFrom',
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Hasta" />,
    accessorKey: 'searchedTo',
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Fecha" />,
    accessorKey: 'issuedAt',
  },
  {
    id: 'actions',
    enableHiding: false,
    enableSorting: false,
    size: 0,
    cell: function CellComponent({ row }) {
      const { t: tPages } = useTranslation('pages');
      const { openConfirmation } = useConfirmationStore();
      const { can: userCan } = useUser();
      const negativa = row.original;

      if (!userCan(TenantPermission.BOOKS_MANAGE) && !userCan(TenantPermission.BOOKS_UPDATE) && !userCan(TenantPermission.BOOKS_DELETE)) {
        return null;
      }

      return (
        <DropdownMenu>
          <DropdownMenuTrigger asChild>
            <Button variant="ghost" size="sm">
              <MoreHorizontalIcon />
              <span className="sr-only">{tPages(($) => $.main.books.includes.columns.actions)}</span>
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent>
            {userCan(TenantPermission.BOOKS_MANAGE) && (
              <DropdownMenuItem
                onSelect={() => {
                  window.open(NegativaPdfController(negativa.id).url, '_blank', 'noopener,noreferrer');
                }}
              >
                <PrinterIcon className="size-3" />
                <span>{tPages(($) => $.main.books.includes.columns.print)}</span>
              </DropdownMenuItem>
            )}
            {userCan(TenantPermission.BOOKS_UPDATE) && (
              <DropdownMenuItem asChild>
                <Link href={NegativaController.edit(negativa.id).url}>
                  <Edit2Icon className="size-3" />
                  <span>{tPages(($) => $.main.books.includes.columns.edit)}</span>
                </Link>
              </DropdownMenuItem>
            )}
            {userCan(TenantPermission.BOOKS_DELETE) && (
              <DropdownMenuItem
                variant="destructive"
                onClick={() => {
                  openConfirmation({
                    title: tPages(($) => $.main.books.includes.columns.areYouSure),
                    description: tPages(($) => $.main.books.includes.columns.cannotBeUndone),
                    actionLabel: tPages(($) => $.main.books.includes.columns.delete),
                    actionVariant: 'destructive',
                    cancelLabel: tPages(($) => $.main.books.includes.columns.cancel),
                    onAction: () => {
                      router.visit(NegativaController.destroy(negativa.id), {
                        preserveState: true,
                        preserveScroll: true,
                      });
                    },
                  });
                }}
              >
                <Trash2Icon className="size-3" />
                <span>{tPages(($) => $.main.books.includes.columns.delete)}</span>
              </DropdownMenuItem>
            )}
          </DropdownMenuContent>
        </DropdownMenu>
      );
    },
  },
];
