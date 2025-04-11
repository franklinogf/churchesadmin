import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { usePermissions } from '@/hooks/use-permissions';

import { SharedData, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';

export function NavMain({ items = [] }: { items: NavItem[] }) {
  const page = usePage<SharedData>();
  const { userCan } = usePermissions();
  return (
    <SidebarGroup className="px-2 py-0">
      <SidebarGroupLabel>Main</SidebarGroupLabel>
      <SidebarMenu>
        {items.map((item) => {
          if (item.permissionNeeded !== undefined && !userCan(item.permissionNeeded)) return null;
          return (
            <SidebarMenuItem key={item.title}>
              <SidebarMenuButton asChild isActive={page.props.ziggy.location.includes(item.href)} tooltip={{ children: item.title }}>
                <Link href={item.href} prefetch>
                  {item.icon && <item.icon />}
                  <span>{item.title}</span>
                </Link>
              </SidebarMenuButton>
            </SidebarMenuItem>
          );
        })}
      </SidebarMenu>
    </SidebarGroup>
  );
}
