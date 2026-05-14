import { type BreadcrumbItem } from '@/types';
import { useMemo } from 'react';

import TenantYearEndController from '@/actions/App/Http/Controllers/Settings/TenantYearEndController';
import HeadingSmall from '@/components/heading-small';
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/church-layout';
import { Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

export default function YearEnd({ currentYear }: { currentYear: number }) {
  const { t } = useTranslation('pages', { keyPrefix: 'settings.church.yearEnd' });

  const breadcrumbs: BreadcrumbItem[] = useMemo(() => [{ title: t(($) => $.churchSettings) }, { title: t(($) => $.yearEndClosing) }], [t]);

  return (
    <AppLayout title={t(($) => $.churchSettings)} breadcrumbs={breadcrumbs}>
      <SettingsLayout>
        <div className="space-y-6">
          <HeadingSmall title={t(($) => $.yearEndClosing)} description={t(($) => $.closeTheCurrentFiscalYear)} />

          <p>{t(($) => $.theCurrentFiscalYearIsYear, { year: currentYear })}</p>
          <p>{t(($) => $.ifYouCloseTheCurrentFiscalYearAllFinancial)}</p>
          <p>{t(($) => $.theNextFiscalYearWillBeYear, { year: currentYear + 1 })}</p>
          <p>{t(($) => $.thisActionCannotBeUndone)}</p>

          <AlertDialog>
            <AlertDialogTrigger asChild>
              <Button variant="destructive">{t(($) => $.closeFiscalYear)}</Button>
            </AlertDialogTrigger>
            <AlertDialogContent>
              <AlertDialogHeader>
                <AlertDialogTitle>{t(($) => $.closeFiscalYear)}</AlertDialogTitle>
                <AlertDialogDescription>{t(($) => $.areYouSureYouWantToCloseTheFiscal)}</AlertDialogDescription>
              </AlertDialogHeader>
              <AlertDialogFooter>
                <AlertDialogCancel>{t(($) => $.cancel)}</AlertDialogCancel>
                <AlertDialogAction asChild>
                  <Link href={TenantYearEndController.update()} method="post">
                    {t(($) => $.closeFiscalYear)}
                  </Link>
                </AlertDialogAction>
              </AlertDialogFooter>
            </AlertDialogContent>
          </AlertDialog>
        </div>
      </SettingsLayout>
    </AppLayout>
  );
}
