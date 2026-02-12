import { Icon } from '@/components/icon';
import { SidebarGroup, SidebarGroupContent, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { useUser } from '@/hooks/use-user';
import type { NavItem } from '@/types';
import { Link } from '@inertiajs/react';

export function SidebarNav({ label, items = [] }: { label?: string; items: NavItem[] }) {
  const { can: userCan } = useUser();
  const filteredItems = items.filter((item) => (item.permissionNeeded !== undefined ? userCan(item.permissionNeeded) : true));
  if (filteredItems.length === 0) return null;
  return (
    <SidebarGroup className="px-2 py-0">
      {label && <SidebarGroupLabel>{label}</SidebarGroupLabel>}
      <SidebarGroupContent>
        <SidebarMenu>
          {filteredItems.map((item) => {
            return (
              <SidebarMenuItem key={item.title}>
                <SidebarMenuButton asChild isActive={false} tooltip={{ children: item.title }}>
                  <Link href={item.href}>
                    {item.icon && <Icon iconNode={item.icon} />}
                    <span>{item.title}</span>
                  </Link>
                </SidebarMenuButton>
              </SidebarMenuItem>
            );
          })}
        </SidebarMenu>
      </SidebarGroupContent>
    </SidebarGroup>
  );
}
