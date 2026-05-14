import PasswordController from '@/actions/App/Http/Controllers/Settings/PasswordController';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { cn } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { useMemo, type PropsWithChildren } from 'react';
import { useTranslation } from 'react-i18next';

export default function SettingsLayout({ children }: PropsWithChildren) {
  const { t: tCommon } = useTranslation('common');
  const sidebarNavItems: NavItem[] = useMemo(
    () => [
      {
        title: tCommon(($) => $.layouts.settings.profileLayout.profile),
        href: ProfileController.edit(),
        icon: null,
      },
      {
        title: tCommon(($) => $.layouts.settings.profileLayout.password),
        href: PasswordController.edit(),
        icon: null,
      },
      {
        title: tCommon(($) => $.layouts.settings.profileLayout.appearance),
        href: '/settings/appearance',
        icon: null,
      },
    ],
    [tCommon],
  );

  return (
    <div className="px-4 py-6">
      <Heading
        title={tCommon(($) => $.layouts.settings.profileLayout.settings)}
        description={tCommon(($) => $.layouts.settings.profileLayout.manageYourProfileAndAccountSettings)}
      />

      <div className="flex flex-col space-y-8 lg:flex-row lg:space-y-0 lg:space-x-12">
        <aside className="w-full max-w-xl lg:w-48">
          <nav className="flex flex-col space-y-1 space-x-0">
            {sidebarNavItems.map((item, index) => (
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
