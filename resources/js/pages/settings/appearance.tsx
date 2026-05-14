import AppearanceTabs from '@/components/appearance-tabs';
import HeadingSmall from '@/components/heading-small';

import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/profile-layout';
import { useTranslation } from 'react-i18next';

export default function Appearance() {
  const { t: tPages } = useTranslation('pages');
  return (
    <AppLayout
      title={tPages(($) => $.settings.appearance.appearanceSettings)}
      breadcrumbs={[{ title: tPages(($) => $.settings.appearance.appearanceSettings2) }]}
    >
      <SettingsLayout>
        <div className="space-y-6">
          <HeadingSmall
            title={tPages(($) => $.settings.appearance.appearanceSettings2)}
            description={tPages(($) => $.settings.appearance.updateAccountsAppearanceSettings)}
          />
          <AppearanceTabs />
        </div>
      </SettingsLayout>
    </AppLayout>
  );
}
