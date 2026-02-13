import CalendarEventController from '@/actions/App/Http/Controllers/CalendarEventController';
import CategoryController from '@/actions/App/Http/Controllers/CategoryController';
import CheckController from '@/actions/App/Http/Controllers/CheckController';
import EmailController from '@/actions/App/Http/Controllers/Communication/EmailController';
import DashboardController from '@/actions/App/Http/Controllers/DashboardController';
import DeactivationCodeController from '@/actions/App/Http/Controllers/DeactivationCodeController';
import ExpenseController from '@/actions/App/Http/Controllers/ExpenseController';
import ExpenseTypeController from '@/actions/App/Http/Controllers/ExpenseTypeController';
import MemberController from '@/actions/App/Http/Controllers/MemberController';
import MissionaryController from '@/actions/App/Http/Controllers/MissionaryController';
import OfferingController from '@/actions/App/Http/Controllers/OfferingController';
import OfferingTypeController from '@/actions/App/Http/Controllers/OfferingTypeController';
import ActivityLogPdfController from '@/actions/App/Http/Controllers/Pdf/ActivityLogPdfController';
import ContributionController from '@/actions/App/Http/Controllers/Pdf/ContributionController';
import EntriesExpensesPdfController from '@/actions/App/Http/Controllers/Pdf/EntriesExpensesPdfController';
import TenantGeneralController from '@/actions/App/Http/Controllers/Settings/TenantGeneralController';
import SkillController from '@/actions/App/Http/Controllers/SkillController';
import UserController from '@/actions/App/Http/Controllers/UserController';
import VisitController from '@/actions/App/Http/Controllers/VisitController';
import WalletController from '@/actions/App/Http/Controllers/WalletController';
import { NavFooter } from '@/components/nav-footer';
import { NavUser } from '@/components/nav-user';
import { SidebarNav } from '@/components/sidebar-nav';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { TenantPermission } from '@/enums/TenantPermission';
import { useTranslations } from '@/hooks/use-translations';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import {
  BanknoteIcon,
  CalendarIcon,
  CogIcon,
  CoinsIcon,
  FileStackIcon,
  HandCoinsIcon,
  HomeIcon,
  LayoutGridIcon,
  ListIcon,
  MailsIcon,
  Users2Icon,
  WalletCardsIcon,
} from 'lucide-react';
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
            href: DashboardController(),
            icon: HomeIcon,
          },
          {
            title: t('Skills'),
            href: SkillController.index(),
            icon: LayoutGridIcon,
            permissionNeeded: TenantPermission.SKILLS_MANAGE,
          },
          {
            title: t('Categories'),
            href: CategoryController.index(),
            icon: LayoutGridIcon,
            permissionNeeded: TenantPermission.CATEGORIES_MANAGE,
          },
          {
            title: t('Members'),
            href: MemberController.index(),
            icon: Users2Icon,
            permissionNeeded: TenantPermission.MEMBERS_MANAGE,
          },
          {
            title: t('Missionaries'),
            href: MissionaryController.index(),
            icon: Users2Icon,
            permissionNeeded: TenantPermission.MISSIONARIES_MANAGE,
          },
          {
            title: t('Users'),
            href: UserController.index(),
            icon: Users2Icon,
            permissionNeeded: TenantPermission.USERS_MANAGE,
          },
          {
            title: t('Visitors'),
            href: VisitController.index(),
            icon: Users2Icon,
            permissionNeeded: TenantPermission.VISITS_MANAGE,
          },
          {
            title: t('Calendar'),
            href: CalendarEventController.index(),
            icon: CalendarIcon,
            permissionNeeded: TenantPermission.CALENDAR_EVENTS_MANAGE,
          },
        ],
      },
      {
        heading: t('Accounting'),
        items: [
          {
            title: t('Wallets'),
            href: WalletController.index(),
            icon: WalletCardsIcon,
          },
          {
            title: t('Offerings'),
            href: OfferingController.index(),
            icon: HandCoinsIcon,
          },
          {
            title: t('Expenses'),
            href: ExpenseController.index(),
            icon: CoinsIcon,
          },
          {
            title: t('Checks'),
            href: CheckController.index(),
            icon: BanknoteIcon,
          },
        ],
      },
      {
        heading: t('Codes'),
        items: [
          {
            title: t('Offering types'),
            href: OfferingTypeController.index(),
            icon: ListIcon,
          },
          {
            title: t('Expense types'),
            href: ExpenseTypeController.index(),
            icon: ListIcon,
          },
          {
            title: t('Deactivation codes'),
            href: DeactivationCodeController.index(),
            icon: ListIcon,
            permissionNeeded: TenantPermission.DEACTIVATION_CODES_MANAGE,
          },
        ],
      },
      {
        heading: t('Reports'),
        items: [
          {
            title: t('General'),
            href: '#', //route('reports'),
            icon: FileStackIcon,
          },
          {
            title: t('Entries and Expenses'),
            href: EntriesExpensesPdfController.index(),
            icon: FileStackIcon,
          },
          {
            title: t('Activity Logs'),
            href: ActivityLogPdfController.index(),
            icon: FileStackIcon,
            permissionNeeded: TenantPermission.ACTIVITY_LOGS_MANAGE,
          },
          {
            title: t('Contributions'),
            href: ContributionController(),
            icon: FileStackIcon,
            // permissionNeeded: TenantPermission.ACTIVITY_LOGS_MANAGE,
          },
        ],
      },
      {
        heading: t('Communication'),
        items: [
          {
            title: t('Emails'),
            href: EmailController.index(),
            icon: MailsIcon,
            permissionNeeded: TenantPermission.EMAILS_MANAGE,
          },
        ],
      },
    ],
    [t],
  );

  const footerNavItems: NavItem[] = [
    {
      title: t('Church Settings'),
      href: TenantGeneralController.edit(),
      icon: CogIcon,
      permissionNeeded: TenantPermission.SETTINGS_MANAGE,
    },
  ];

  return (
    <Sidebar collapsible="icon" variant="inset">
      <SidebarHeader>
        <SidebarMenu>
          <SidebarMenuItem>
            <SidebarMenuButton size="lg" asChild>
              <Link href={DashboardController()} prefetch>
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
