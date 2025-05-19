import AppLayout from '@/layouts/app-layout';

import { CreateCheckLayoutForm } from '@/components/forms/create-check-layout-form';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { useIsMobile } from '@/hooks/use-mobile';
import { useTranslations } from '@/hooks/use-translations';
import { type BreadcrumbItem, type SelectOption } from '@/types';
import type { CheckLayout } from '@/types/models/check-layout';
import type { Wallet } from '@/types/models/wallet';
import { Link, router } from '@inertiajs/react';
import { AlertCircleIcon } from 'lucide-react';
import { useMemo, useState } from 'react';
import { CheckLayoutEditor } from './components/check-layout-editor';

const NEW_LAYOUT = 'new_layout';
interface CheckLayoutProps {
  checkLayouts: SelectOption[];
  checkLayout: CheckLayout | null;
  wallet: Wallet;
}

export default function CheckLayout({ checkLayouts, wallet, checkLayout }: CheckLayoutProps) {
  const { t } = useTranslations<string>();
  const isMobile = useIsMobile();
  const [activeLayout, setActiveLayout] = useState(checkLayout?.id.toString() || NEW_LAYOUT);

  function handleChangeActiveLayout(value: string) {
    router.get(route('wallets.check.edit', [wallet.id]), { layout: value }, { replace: true });
    setActiveLayout(value);
  }

  const breadcrumbs: BreadcrumbItem[] = useMemo(() => [{ title: t('Wallets'), href: route('wallets.index') }, { title: t('Check layout') }], [t]);
  const isWalletLayout = wallet.checkLayout?.id.toString() === activeLayout;

  return (
    <AppLayout title={t('Check layout')} breadcrumbs={breadcrumbs}>
      {isMobile ? (
        <div className="space-y-6">
          <Alert variant="warning">
            <AlertCircleIcon className="size-4" />
            <AlertTitle>{t('Check layout is not available on mobile devices')}</AlertTitle>
            <AlertDescription>{t('Please use a desktop device to edit the check layout')}</AlertDescription>
          </Alert>

          {wallet.checkLayout ? (
            <p>{t('Using the :name layout', { name: wallet.checkLayout?.name })}</p>
          ) : (
            <p>{t('No check layout selected for this wallet')}</p>
          )}
        </div>
      ) : (
        <div className="space-y-6">
          <div className="mx-auto max-w-xl space-y-6">
            <PageTitle description={t('Here you can update the printing check layout')}>{t('Check layout')}</PageTitle>

            <section className="space-y-4">
              <div className="mt-8 flex items-center gap-2">
                <SelectField
                  className="grow"
                  label={t('Select the layout you want to use for this wallet or create a new one')}
                  value={activeLayout}
                  onChange={handleChangeActiveLayout}
                  options={[{ value: NEW_LAYOUT, label: t('Create new layout') }, ...checkLayouts]}
                />
                {activeLayout !== NEW_LAYOUT && (
                  <Button disabled={isWalletLayout} variant="outline" className="self-end" asChild>
                    <Link
                      href={route('wallets.check.change-layout', [wallet.id])}
                      method="put"
                      data={{ check_layout_id: activeLayout }}
                      preserveScroll
                      as="button"
                    >
                      {isWalletLayout ? t('You are using this layout') : t('Use this layout')}
                    </Link>
                  </Button>
                )}
              </div>

              {activeLayout === NEW_LAYOUT && <CreateCheckLayoutForm walletId={wallet.id} />}
            </section>
          </div>
          {activeLayout !== NEW_LAYOUT && checkLayout && <CheckLayoutEditor checkLayout={checkLayout} />}
        </div>
      )}
    </AppLayout>
  );
}
