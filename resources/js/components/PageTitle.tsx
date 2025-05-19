import { useTranslations } from '@/hooks/use-translations';
import { cn } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import { Button } from './ui/button';

interface PageTitleProps {
  children: React.ReactNode;
  className?: string;
  labelClassName?: string;
  backLink?: string;
  description?: string;
}

export function PageTitle({ children, className, backLink, labelClassName, description }: PageTitleProps) {
  const { t } = useTranslations<string>();
  return (
    <header className={cn('my-2 flex flex-col items-center gap-2', className)}>
      <h1 className={cn('my-2 text-center text-4xl font-bold', labelClassName)}>{children}</h1>
      {description && <p className="text-muted-foreground">{description}</p>}
      {backLink && (
        <Button variant="outline" size="sm" asChild>
          <Link href={backLink}>{t('Ir atrás')}</Link>
        </Button>
      )}
    </header>
  );
}
