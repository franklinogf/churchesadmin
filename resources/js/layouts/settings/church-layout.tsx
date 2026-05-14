import TenantGeneralController from '@/actions/App/Http/Controllers/Settings/TenantGeneralController';
import TenantLanguageController from '@/actions/App/Http/Controllers/Settings/TenantLanguageController';
import TenantYearEndController from '@/actions/App/Http/Controllers/Settings/TenantYearEndController';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import { cn } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { useMemo, type PropsWithChildren } from 'react';
import { useTranslation } from 'react-i18next';

export default function SettingsLayout({ children }: PropsWithChildren) {
  const { t: tCommon } = useTranslation('common');
  const { can: userCan } = useUser();

  const sidebarNavItems: NavItem[] = useMemo<NavItem[]>(
    () => [
      {
        title: tCommon(($) => $.layouts.settings.churchLayout.general),
        href: TenantGeneralController.edit(),
      },
      {
        title: tCommon(($) => $.layouts.settings.churchLayout.language),
        href: TenantLanguageController.edit(),
        permissionNeeded: TenantPermission.SETTINGS_CHANGE_LANGUAGE,
      },
      {
        title: tCommon(($) => $.layouts.settings.churchLayout.yearEndClosing),
        href: TenantYearEndController.edit(),
        permissionNeeded: TenantPermission.SETTINGS_CLOSE_YEAR,
      },
      //   {
      //     title: tCommon(($) => $.layouts.settings.churchLayout.contactInformation),
      //     href: route('church.contact.edit'),
      //   },
      //   {
      //     title: tCommon(($) => $.layouts.settings.churchLayout.socialMedia),
      //     href: route('church.social.edit'),
      //   },
    ],
    [tCommon],
  );

  // When server-side rendering, we only render the layout on the client...
  if (typeof window === 'undefined') {
    return null;
  }
  const filteredItems = sidebarNavItems.filter((item) => (item.permissionNeeded !== undefined ? userCan(item.permissionNeeded) : true));

  return (
    <div className="px-4 py-6">
      <Heading
        title={tCommon(($) => $.layouts.settings.churchLayout.churchSettings)}
        description={tCommon(($) => $.layouts.settings.churchLayout.manageChurchInformation)}
      />

      <div className="flex flex-col space-y-8 lg:flex-row lg:space-y-0 lg:space-x-12">
        <aside className="w-full max-w-xl lg:w-48">
          <nav className="flex flex-col space-y-1 space-x-0">
            {filteredItems.map((item, index) => (
              <Button
                key={`${item.href}-${index}`}
                size="sm"
                variant="ghost"
                asChild
                className={cn('w-full justify-start', {
                  'bg-muted': false,
                })}
              >
                <Link href={item.href} prefetch>
                  {item.title}
                </Link>
              </Button>
            ))}
          </nav>
        </aside>

        <Separator className="my-6 md:hidden" />

        <div className="flex-1 md:max-w-2xl">
          <section className="max-w-xl space-y-12">{children}</section>
        </div>
      </div>
    </div>
  );
}
