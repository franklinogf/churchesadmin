import DashboardController from '@/actions/App/Http/Controllers/DashboardController';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { router } from '@inertiajs/react';
import { AlertTriangleIcon, ArrowLeft } from 'lucide-react';
import { useTranslation } from 'react-i18next';

export default function ErrorPage({ status }: { status: 503 | 500 | 404 | 403 }) {
  const { t } = useTranslation('error');

  const title = t(($) => $[status].message);
  const description = t(($) => $[status].description);

  const isServerError = status === 500 || status === 503;

  const handleGoHome = () => {
    router.visit(DashboardController());
  };

  return (
    <div className="flex min-h-screen items-center px-4 py-12 sm:px-6 md:px-8 lg:px-12 xl:px-16">
      <div className="w-full space-y-6 text-center">
        <div className="space-y-3">
          <div
            className={cn('bg-muted mx-auto flex h-20 w-20 items-center justify-center rounded-2xl shadow-sm', {
              'bg-red-100': isServerError,
              'bg-slate-100': !isServerError,
            })}
          >
            <AlertTriangleIcon
              className={cn('size-10', {
                'text-red-500': isServerError,
                'text-slate-500': !isServerError,
              })}
            />
          </div>
          <PageTitle className="text-4xl font-bold tracking-tighter sm:text-5xl">{title}</PageTitle>
          <p className="text-gray-500">{description}</p>
          <Button variant="default" size="lg" onClick={handleGoHome} className="gap-2">
            <ArrowLeft className="h-4 w-4" />
            {t(($) => $.actions.home)}
          </Button>
        </div>
      </div>
    </div>
  );
}
