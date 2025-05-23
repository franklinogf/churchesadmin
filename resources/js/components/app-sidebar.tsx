import { NavFooter } from '@/components/nav-footer';
import { NavUser } from '@/components/nav-user';
import { SidebarNav } from '@/components/sidebar-nav';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { UserPermission } from '@/enums/user';
import { useTranslations } from '@/hooks/use-translations';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { BanknoteIcon, CogIcon, CoinsIcon, HandCoinsIcon, LayoutGridIcon, ListIcon, MailsIcon, Users2Icon, WalletCardsIcon } from 'lucide-react';
import { useMemo } from 'react';
import AppLogo from './app-logo';

export function AppSidebar() {
  const { t } = useTranslations();
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
            permissionNeeded: UserPermission.SKILLS_MANAGE,
          },
          {
            title: t('Categories'),
            href: route('categories.index'),
            icon: LayoutGridIcon,
            permissionNeeded: UserPermission.CATEGORIES_MANAGE,
          },
          {
            title: t('Members'),
            href: route('members.index'),
            icon: Users2Icon,
            permissionNeeded: UserPermission.MEMBERS_MANAGE,
          },
          {
            title: t('Missionaries'),
            href: route('missionaries.index'),
            icon: Users2Icon,
            permissionNeeded: UserPermission.MISSIONARIES_MANAGE,
          },
          {
            title: t('Users'),
            href: route('users.index'),
            icon: Users2Icon,
            permissionNeeded: UserPermission.USERS_MANAGE,
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
          {
            title: t('Checks'),
            href: route('checks.index'),
            icon: BanknoteIcon,
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
      {
        heading: t('Communication'),
        items: [
          {
            title: t('Emails'),
            href: route('communication.emails.index'),
            icon: MailsIcon,
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
