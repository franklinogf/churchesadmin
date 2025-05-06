import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { MoreHorizontalIcon } from 'lucide-react';
export function DatatableActionsDropdown({ children }: { children: React.ReactNode }) {
  const { t } = useLaravelReactI18n();
  return (
    <DropdownMenu>
      <DropdownMenuTrigger asChild>
        <Button variant="ghost" size="sm">
          <MoreHorizontalIcon />
          <span className="sr-only">{t('Actions')}</span>
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent>{children}</DropdownMenuContent>
    </DropdownMenu>
  );
}
