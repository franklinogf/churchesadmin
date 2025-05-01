import { NavFooter } from '@/components/nav-footer';
import { NavUser } from '@/components/nav-user';
import { SidebarNav } from '@/components/sidebar-nav';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { UserPermission } from '@/enums/user';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { CogIcon, CoinsIcon, HandCoinsIcon, LayoutGridIcon, ListIcon, Users2Icon, WalletCardsIcon } from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavItem[] = [
  {
    title: 'Dashboard',
    href: route('dashboard'),
    icon: LayoutGridIcon,
  },
  {
    title: 'Skills',
    href: route('skills.index'),
    icon: LayoutGridIcon,
    permissionNeeded: UserPermission.MANAGE_SKILLS,
  },
  {
    title: 'Categories',
    href: route('categories.index'),
    icon: LayoutGridIcon,
    permissionNeeded: UserPermission.MANAGE_CATEGORIES,
  },
  {
    title: 'Members',
    href: route('members.index'),
    icon: Users2Icon,
    permissionNeeded: UserPermission.MANAGE_MEMBERS,
  },
  {
    title: 'Missionaries',
    href: route('missionaries.index'),
    icon: Users2Icon,
    permissionNeeded: UserPermission.MANAGE_MISSIONARIES,
  },
  {
    title: 'Users',
    href: route('users.index'),
    icon: Users2Icon,
    permissionNeeded: UserPermission.MANAGE_USERS,
  },
];

const accountingNavItems: NavItem[] = [
  {
    title: 'Wallets',
    href: route('wallets.index'),
    icon: WalletCardsIcon,
  },
  {
    title: 'Offerings',
    href: route('offerings.index'),
    icon: HandCoinsIcon,
  },
  {
    title: 'Expenses',
    href: route('expenses.index'),
    icon: CoinsIcon,
  },
];

const codesNavItems: NavItem[] = [
  {
    title: 'Offering types',
    href: route('codes.offeringTypes.index'),
    icon: ListIcon,
  },
  {
    title: 'Expense types',
    href: route('codes.expenseTypes.index'),
    icon: ListIcon,
  },
];

const footerNavItems: NavItem[] = [
  {
    title: 'Church Settings',
    href: route('church.language.edit'),
    icon: CogIcon,
  },
];
export function AppSidebar() {
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
        <SidebarNav label="Main" items={mainNavItems} />
        <SidebarNav label="Accounting" items={accountingNavItems} />
        <SidebarNav label="Codes" items={codesNavItems} />
      </SidebarContent>

      <SidebarFooter>
        <NavFooter items={footerNavItems} />
        <NavUser />
      </SidebarFooter>
    </Sidebar>
  );
}
