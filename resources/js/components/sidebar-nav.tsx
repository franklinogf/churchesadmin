import { Icon } from '@/components/icon';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import {
  SidebarGroup,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarMenuSub,
  SidebarMenuSubButton,
  SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { useUser } from '@/hooks/use-permissions';
import type { NavGroup, NavItem, NavMenu, SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { ChevronRightIcon } from 'lucide-react';

function isGroup(el: NavMenu): el is NavGroup {
  return 'items' in el;
}

export function SidebarNav({ label, items = [] }: { label: string; items: NavMenu[] }) {
  const page = usePage<SharedData>();
  const { can: userCan } = useUser();
  return (
    <SidebarGroup className="px-2 py-0">
      <SidebarGroupLabel>{label}</SidebarGroupLabel>
      <SidebarMenu>
        {items.map((item) => {
          if (isGroup(item)) {
            return <NavCollapsible key={item.title} title={item.title} items={item.items} />;
          }

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
    </SidebarGroup>
  );
}

function NavCollapsible({ title, items }: { title: string; items: NavItem[] }) {
  const page = usePage<SharedData>();
  const { can: userCan } = useUser();
  return (
    <Collapsible defaultOpen className="group/collapsible">
      <SidebarMenuItem>
        <CollapsibleTrigger asChild>
          <SidebarMenuButton>
            {title}
            <ChevronRightIcon className="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
          </SidebarMenuButton>
        </CollapsibleTrigger>
        <CollapsibleContent>
          <SidebarMenuSub>
            {items.map((item) => {
              if (item.permissionNeeded !== undefined && !userCan(item.permissionNeeded)) return null;
              return (
                <SidebarMenuSubItem key={item.title}>
                  <SidebarMenuSubButton asChild isActive={page.props.ziggy.location.includes(item.href)}>
                    <Link href={item.href} prefetch>
                      {item.icon && <Icon iconNode={item.icon} />}
                      <span>{item.title}</span>
                    </Link>
                  </SidebarMenuSubButton>
                </SidebarMenuSubItem>
              );
            })}
          </SidebarMenuSub>
        </CollapsibleContent>
      </SidebarMenuItem>
    </Collapsible>
  );
}
