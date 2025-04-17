import { NavFooter } from '@/components/nav-footer';
import { NavUser } from '@/components/nav-user';
import { SidebarNav } from '@/components/sidebar-nav';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { UserPermission } from '@/enums/user';
import type { NavItem, NavMenu } from '@/types';
import { Link } from '@inertiajs/react';
import { LayoutGridIcon, Users2Icon, WalletCardsIcon } from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavMenu[] = [
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
  {
    title: 'Accounting',
    items: [
      {
        title: 'Wallets',
        href: route('wallets.index'),
        icon: WalletCardsIcon,
      },
    ],
  },
];

const footerNavItems: NavItem[] = [];
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
      </SidebarContent>

      <SidebarFooter>
        <NavFooter items={footerNavItems} className="mt-auto" />
        <NavUser />
      </SidebarFooter>
    </Sidebar>
  );
}
