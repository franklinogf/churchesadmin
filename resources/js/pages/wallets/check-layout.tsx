import AppLayout from '@/layouts/app-layout';

import WalletCheckLayoutController from '@/actions/App/Http/Controllers/WalletCheckLayoutController';
import WalletController from '@/actions/App/Http/Controllers/WalletController';
import { CreateCheckLayoutForm } from '@/components/forms/create-check-layout-form';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { useIsMobile } from '@/hooks/use-mobile';
import { type BreadcrumbItem, type SelectOption } from '@/types';
import type { CheckLayout } from '@/types/models/check-layout';
import type { Wallet } from '@/types/models/wallet';
import { Link, router } from '@inertiajs/react';
import { AlertCircleIcon } from 'lucide-react';
import { useMemo, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { CheckLayoutEditor } from './components/check-layout-editor';

const NEW_LAYOUT = 'new_layout';
interface CheckLayoutProps {
  checkLayouts: SelectOption[];
  checkLayout: CheckLayout | null;
  wallet: Wallet;
}

export default function CheckLayout({ checkLayouts, wallet, checkLayout }: CheckLayoutProps) {
  const { t: tPages } = useTranslation('pages');
  const isMobile = useIsMobile();
  const [activeLayout, setActiveLayout] = useState(checkLayout?.id.toString() || NEW_LAYOUT);

  function handleChangeActiveLayout(value: string) {
    router.visit(WalletCheckLayoutController.edit(wallet.id, { mergeQuery: { layout: value } }), { replace: true });
    setActiveLayout(value);
  }

  const breadcrumbs: BreadcrumbItem[] = useMemo(
    () => [
      { title: tPages(($) => $.wallets.checkLayout.wallets), href: WalletController.index().url },
      { title: tPages(($) => $.wallets.checkLayout.checkLayout) },
    ],
    [tPages],
  );
  const isWalletLayout = wallet.checkLayout?.id.toString() === activeLayout;

  return (
    <AppLayout title={tPages(($) => $.wallets.checkLayout.checkLayout)} breadcrumbs={breadcrumbs}>
      {isMobile ? (
        <div className="space-y-6">
          <Alert variant="warning">
            <AlertCircleIcon className="size-4" />
            <AlertTitle>{tPages(($) => $.wallets.checkLayout.checkLayoutIsNotAvailableOnMobileDevices)}</AlertTitle>
            <AlertDescription>{tPages(($) => $.wallets.checkLayout.pleaseUseADesktopDeviceToEditTheCheck)}</AlertDescription>
          </Alert>

          {wallet.checkLayout ? (
            <p>{tPages(($) => $.wallets.checkLayout.usingTheNameLayout, { name: wallet.checkLayout?.name })}</p>
          ) : (
            <p>{tPages(($) => $.wallets.checkLayout.noCheckLayoutSelectedForThisWallet)}</p>
          )}
        </div>
      ) : (
        <div className="space-y-6">
          <div className="mx-auto max-w-xl space-y-6">
            <PageTitle description={tPages(($) => $.wallets.checkLayout.hereYouCanUpdateThePrintingCheckLayout)}>
              {tPages(($) => $.wallets.checkLayout.checkLayout)}
            </PageTitle>

            <section className="space-y-4">
              <div className="mt-8 flex items-center gap-2">
                <SelectField
                  className="grow"
                  label={tPages(($) => $.wallets.checkLayout.selectTheLayoutYouWantToUseForThis)}
                  value={activeLayout}
                  onValueChange={handleChangeActiveLayout}
                  options={[
                    {
                      value: NEW_LAYOUT,
                      label: tPages(($) => $.wallets.checkLayout.createANewModel, { model: tPages(($) => $.wallets.checkLayout.checkLayout) }),
                    },
                    ...checkLayouts,
                  ]}
                />
                {activeLayout !== NEW_LAYOUT && (
                  <Button disabled={isWalletLayout} variant="outline" className="self-end" asChild>
                    <Link
                      href={WalletCheckLayoutController.update(wallet.id)}
                      method="put"
                      data={{ check_layout_id: activeLayout }}
                      preserveScroll
                      as="button"
                    >
                      {isWalletLayout
                        ? tPages(($) => $.wallets.checkLayout.youAreUsingThisLayout)
                        : tPages(($) => $.wallets.checkLayout.useThisLayout)}
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
