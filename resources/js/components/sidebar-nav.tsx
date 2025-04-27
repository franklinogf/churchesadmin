import { Icon } from '@/components/icon';
import { SidebarGroup, SidebarGroupContent, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { useUser } from '@/hooks/use-permissions';
import type { NavItem, SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';

export function SidebarNav({ label, items = [] }: { label?: string; items: NavItem[] }) {
  const page = usePage<SharedData>();
  const { can: userCan } = useUser();
  return (
    <SidebarGroup className="px-2 py-0">
      {label && <SidebarGroupLabel>{label}</SidebarGroupLabel>}
      <SidebarGroupContent>
        <SidebarMenu>
          {items.map((item) => {
            if (item.permissionNeeded !== undefined && !userCan(item.permissionNeeded)) return null;

            return (
              <SidebarMenuItem key={item.title}>
                <SidebarMenuButton asChild isActive={page.props.ziggy.location.includes(item.href)} tooltip={{ children: item.title }}>
                  <Link href={item.href} prefetch>
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
