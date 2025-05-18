import AppearanceTabs from '@/components/appearance-tabs';
import HeadingSmall from '@/components/heading-small';

import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/profile-layout';
import { useLaravelReactI18n } from 'laravel-react-i18n';

export default function Appearance() {
  const { t } = useLaravelReactI18n();
  return (
    <AppLayout title={t('Appearance Settings')} breadcrumbs={[{ title: t('Appearance settings') }]}>
      <SettingsLayout>
        <div className="space-y-6">
          <HeadingSmall title={t('Appearance settings')} description={t('Update :model', { model: t("account's appearance settings") })} />
          <AppearanceTabs />
        </div>
      </SettingsLayout>
    </AppLayout>
  );
}
