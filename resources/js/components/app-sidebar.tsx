import { NavFooter } from '@/components/nav-footer';
import { NavUser } from '@/components/nav-user';
import { SidebarNav } from '@/components/sidebar-nav';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { UserPermission } from '@/enums/user';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { CogIcon, CoinsIcon, HandCoinsIcon, LayoutGridIcon, ListIcon, Users2Icon, WalletCardsIcon } from 'lucide-react';
import { useMemo } from 'react';
import AppLogo from './app-logo';

export function AppSidebar() {
  const { t } = useLaravelReactI18n();
  const navs: { heading: string; items: NavItem[] }[] = useMemo(
    () => [
      {
        heading: t('Main'),
        items: [
          {
            title: t('Dashboard'),
            href: route('dashboard'),
            icon: LayoutGridIcon,
          },
          {
            title: t('Skills'),
            href: route('skills.index'),
            icon: LayoutGridIcon,
            permissionNeeded: UserPermission.MANAGE_SKILLS,
          },
          {
            title: t('Categories'),
            href: route('categories.index'),
            icon: LayoutGridIcon,
            permissionNeeded: UserPermission.MANAGE_CATEGORIES,
          },
          {
            title: t('Members'),
            href: route('members.index'),
            icon: Users2Icon,
            permissionNeeded: UserPermission.MANAGE_MEMBERS,
          },
          {
            title: t('Missionaries'),
            href: route('missionaries.index'),
            icon: Users2Icon,
            permissionNeeded: UserPermission.MANAGE_MISSIONARIES,
          },
          {
            title: t('Users'),
            href: route('users.index'),
            icon: Users2Icon,
            permissionNeeded: UserPermission.MANAGE_USERS,
          },
        ],
      },
      {
        heading: t('Accounting'),
        items: [
          {
            title: t('Wallets'),
            href: route('wallets.index'),
            icon: WalletCardsIcon,
          },
          {
            title: t('Offerings'),
            href: route('offerings.index'),
            icon: HandCoinsIcon,
          },
          {
            title: t('Expenses'),
            href: route('expenses.index'),
            icon: CoinsIcon,
          },
        ],
      },
      {
        heading: t('Codes'),
        items: [
          {
            title: t('Offering types'),
            href: route('codes.offeringTypes.index'),
            icon: ListIcon,
          },
          {
            title: t('Expense types'),
            href: route('codes.expenseTypes.index'),
            icon: ListIcon,
          },
        ],
      },
    ],
    [t],
  );

  const footerNavItems: NavItem[] = useMemo(
    () => [
      {
        title: t('Church Settings'),
        href: route('church.settings'),
        icon: CogIcon,
      },
    ],
    [t],
  );
  return (
    <Sidebar collapsible="icon" variant="inset">
      <SidebarHeader>
        <SidebarMenu>
          <SidebarMenuItem>
            <SidebarMenuButton size="lg" asChild>
              <Link href={route('dashboard')} prefetch>
                <AppLogo />
              </Link>
            </SidebarMenuButton>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarHeader>

      <SidebarContent>
        {navs.map((nav) => (
          <SidebarNav key={nav.heading} label={nav.heading} items={nav.items} />
        ))}
      </SidebarContent>

      <SidebarFooter>
        <NavFooter items={footerNavItems} />
        <NavUser />
      </SidebarFooter>
    </Sidebar>
  );
}
