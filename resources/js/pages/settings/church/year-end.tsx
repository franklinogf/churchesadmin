import { type BreadcrumbItem } from '@/types';
import { useMemo } from 'react';

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
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/church-layout';
import { Link } from '@inertiajs/react';

export default function YearEnd({ currentYear }: { currentYear: number }) {
  const { t } = useTranslations();

  const breadcrumbs: BreadcrumbItem[] = useMemo(() => [{ title: t('Church Settings') }, { title: t('Year End Closing') }], [t]);

  return (
    <AppLayout title={t('Church Settings')} breadcrumbs={breadcrumbs}>
      <SettingsLayout>
        <div className="space-y-6">
          <HeadingSmall title={t('Year End Closing')} description={t('Close the current fiscal year')} />

          <p>{t('The current fiscal year is :year', { year: currentYear })}</p>
          <p>{t('If you close the current fiscal year, all financial data will be archived and a new fiscal year will be created.')}</p>
          <p>{t('The next fiscal year will be :year', { year: currentYear + 1 })}</p>
          <p>{t('This action cannot be undone.')}</p>

          <AlertDialog>
            <AlertDialogTrigger asChild>
              <Button variant="destructive">{t('Close Fiscal Year')}</Button>
            </AlertDialogTrigger>
            <AlertDialogContent>
              <AlertDialogHeader>
                <AlertDialogTitle>{t('Close Fiscal Year')}</AlertDialogTitle>
                <AlertDialogDescription>{t('Are you sure you want to close the fiscal year? This action cannot be undone.')}</AlertDialogDescription>
              </AlertDialogHeader>
              <AlertDialogFooter>
                <AlertDialogCancel>{t('Cancel')}</AlertDialogCancel>
                <AlertDialogAction asChild>
                  <Link href={route('church.general.year-end.update')} method="post">
                    {t('Close Fiscal Year')}
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
