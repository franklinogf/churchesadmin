import FirstCommunionCertificateController from '@/actions/App/Http/Controllers/FirstCommunionCertificateController';
import FirstCommunionCertificatePdfController from '@/actions/App/Http/Controllers/Pdf/FirstCommunionCertificatePdfController';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import useConfirmationStore from '@/stores/confirmation-store';
import type { FirstCommunionCertificate } from '@/types/models/first-communion-certificate';
import { Link, router } from '@inertiajs/react';
import type { ColumnDef } from '@tanstack/react-table';
import { Edit2Icon, MoreHorizontalIcon, PrinterIcon, Trash2Icon } from 'lucide-react';
import { useTranslation } from 'react-i18next';

export const columns: ColumnDef<FirstCommunionCertificate>[] = [
  {
    enableHiding: false,
    header: ({ column }) => <DatatableHeader column={column} title="Nombre" />,
    accessorKey: 'communicantName',
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Sacerdote" />,
    accessorKey: 'priest',
  },
  {
    header: ({ column }) => <DatatableHeader column={column} title="Fecha" />,
    accessorKey: 'communionAt',
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
      const firstCommunionCertificate = row.original;

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
                  window.open(FirstCommunionCertificatePdfController(firstCommunionCertificate.id).url, '_blank', 'noopener,noreferrer');
                }}
              >
                <PrinterIcon className="size-3" />
                <span>{tPages(($) => $.main.books.includes.columns.print)}</span>
              </DropdownMenuItem>
            )}
            {userCan(TenantPermission.BOOKS_UPDATE) && (
              <DropdownMenuItem asChild>
                <Link href={FirstCommunionCertificateController.edit(firstCommunionCertificate.id).url}>
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
                      router.visit(FirstCommunionCertificateController.destroy(firstCommunionCertificate.id), {
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
