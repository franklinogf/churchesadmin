import { cn } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { Button } from './ui/button';

type Title = {
  children: React.ReactNode;
  className?: string;
  labelClassName: never;
  backLink?: never;
};
type TitleWithBackLink = {
  children: React.ReactNode;
  className?: string;
  labelClassName?: string;
  backLink?: string;
};
type PageTitleProps = Title | TitleWithBackLink;
export function PageTitle({ children, className, backLink, labelClassName }: PageTitleProps) {
  const { t } = useLaravelReactI18n();
  if (backLink) {
    return (
      <div className={cn('my-2 flex flex-col items-center gap-2', className)}>
        <Title className={labelClassName}>{children}</Title>
        <Button variant="outline" size="sm" asChild>
          <Link href={backLink}>{t('Ir atrás')}</Link>
        </Button>
      </div>
    );
  }
  return <Title className={className}>{children}</Title>;
}

function Title({ children, className }: { children: React.ReactNode; className?: string }) {
  return <h1 className={cn('my-2 text-center text-4xl font-bold', className)}>{children}</h1>;
}
