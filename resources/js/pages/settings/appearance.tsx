import AppearanceTabs from '@/components/appearance-tabs';
import HeadingSmall from '@/components/heading-small';

import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/profile-layout';

export default function Appearance() {
  const { t } = useTranslations<string>();
  return (
    <AppLayout title={t('Appearance Settings')} breadcrumbs={[{ title: t('Appearance settings') }]}>
      <SettingsLayout>
        <div className="space-y-6">
          <HeadingSmall title={t('Appearance settings')} description={t("Update account's appearance settings")} />
          <AppearanceTabs />
        </div>
      </SettingsLayout>
    </AppLayout>
  );
}
