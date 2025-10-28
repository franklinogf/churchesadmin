import { Icon } from '@/components/icon';
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { useUser } from '@/hooks/use-user';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { type ComponentPropsWithoutRef } from 'react';

export function NavFooter({
  items,
  className,
  ...props
}: ComponentPropsWithoutRef<typeof SidebarMenu> & {
  items: NavItem[];
}) {
  const { can: userCan } = useUser();
  const filteredItems = items.filter((item) => (item.permissionNeeded !== undefined ? userCan(item.permissionNeeded) : true));
  return (
    <SidebarMenu {...props} className={className}>
      {filteredItems.map((item) => (
        <SidebarMenuItem key={item.title}>
          <SidebarMenuButton asChild className="text-neutral-600 hover:text-neutral-800 dark:text-neutral-300 dark:hover:text-neutral-100">
            <Link href={item.href}>
              {item.icon && <Icon iconNode={item.icon} />}
              <span>{item.title}</span>
            </Link>
          </SidebarMenuButton>
        </SidebarMenuItem>
      ))}
    </SidebarMenu>
  );
}
