import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useTranslations } from '@/hooks/use-translations';
import { MoreHorizontalIcon } from 'lucide-react';
import { DatatableCell } from './DatatableCell';
export function DatatableActionsDropdown({ children }: { children: React.ReactNode }) {
  const { t } = useTranslations();
  return (
    <DatatableCell justify="end">
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <Button variant="ghost" size="sm">
            <MoreHorizontalIcon />
            <span className="sr-only">{t('datatable.actions')}</span>
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent>{children}</DropdownMenuContent>
      </DropdownMenu>
    </DatatableCell>
  );
}
