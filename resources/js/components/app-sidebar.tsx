import BooksController from '@/actions/App/Http/Controllers/BooksController';
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
import ReportController from '@/actions/App/Http/Controllers/ReportController';
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
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import {
  BanknoteIcon,
  BookOpenIcon,
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
import { useTranslation } from 'react-i18next';
import AppLogo from './app-logo';

export function AppSidebar() {
  const { t: tCommon } = useTranslation('common');
  const navs: { heading: string; items: NavItem[] }[] = useMemo(
    () => [
      {
        heading: tCommon(($) => $.components.appSidebar.main),
        items: [
          {
            title: tCommon(($) => $.components.appSidebar.dashboard),
            href: DashboardController(),
            icon: HomeIcon,
          },
          {
            title: tCommon(($) => $.components.appSidebar.skills),
            href: SkillController.index(),
            icon: LayoutGridIcon,
            permissionNeeded: TenantPermission.SKILLS_MANAGE,
          },
          {
            title: tCommon(($) => $.components.appSidebar.books),
            href: BooksController(),
            icon: BookOpenIcon,
            permissionNeeded: TenantPermission.BOOKS_MANAGE,
            featureNeeded: 'books',
          },
          {
            title: tCommon(($) => $.components.appSidebar.categories),
            href: CategoryController.index(),
            icon: LayoutGridIcon,
            permissionNeeded: TenantPermission.CATEGORIES_MANAGE,
          },
          {
            title: tCommon(($) => $.components.appSidebar.members),
            href: MemberController.index(),
            icon: Users2Icon,
            permissionNeeded: TenantPermission.MEMBERS_MANAGE,
          },
          {
            title: tCommon(($) => $.components.appSidebar.missionaries),
            href: MissionaryController.index(),
            icon: Users2Icon,
            permissionNeeded: TenantPermission.MISSIONARIES_MANAGE,
          },
          {
            title: tCommon(($) => $.components.appSidebar.users),
            href: UserController.index(),
            icon: Users2Icon,
            permissionNeeded: TenantPermission.USERS_MANAGE,
          },
          {
            title: tCommon(($) => $.components.appSidebar.visitors),
            href: VisitController.index(),
            icon: Users2Icon,
            permissionNeeded: TenantPermission.VISITS_MANAGE,
          },
          {
            title: tCommon(($) => $.components.appSidebar.calendar),
            href: CalendarEventController.index(),
            icon: CalendarIcon,
            permissionNeeded: TenantPermission.CALENDAR_EVENTS_MANAGE,
          },
        ],
      },
      {
        heading: tCommon(($) => $.components.appSidebar.accounting),
        items: [
          {
            title: tCommon(($) => $.components.appSidebar.wallets),
            href: WalletController.index(),
            icon: WalletCardsIcon,
          },
          {
            title: tCommon(($) => $.components.appSidebar.offerings),
            href: OfferingController.index(),
            icon: HandCoinsIcon,
          },
          {
            title: tCommon(($) => $.components.appSidebar.expenses),
            href: ExpenseController.index(),
            icon: CoinsIcon,
          },
          {
            title: tCommon(($) => $.components.appSidebar.checks),
            href: CheckController.index(),
            icon: BanknoteIcon,
          },
        ],
      },
      {
        heading: tCommon(($) => $.components.appSidebar.codes),
        items: [
          {
            title: tCommon(($) => $.components.appSidebar.offeringTypes),
            href: OfferingTypeController.index(),
            icon: ListIcon,
          },
          {
            title: tCommon(($) => $.components.appSidebar.expenseTypes),
            href: ExpenseTypeController.index(),
            icon: ListIcon,
          },
          {
            title: tCommon(($) => $.components.appSidebar.deactivationCodes),
            href: DeactivationCodeController.index(),
            icon: ListIcon,
            permissionNeeded: TenantPermission.DEACTIVATION_CODES_MANAGE,
          },
        ],
      },
      {
        heading: tCommon(($) => $.components.appSidebar.reports),
        items: [
          {
            title: tCommon(($) => $.components.appSidebar.general),
            href: ReportController(),
            icon: FileStackIcon,
          },
          {
            title: tCommon(($) => $.components.appSidebar.entriesAndExpenses),
            href: EntriesExpensesPdfController.index(),
            icon: FileStackIcon,
          },
          {
            title: tCommon(($) => $.components.appSidebar.activityLogs),
            href: ActivityLogPdfController.index(),
            icon: FileStackIcon,
            permissionNeeded: TenantPermission.ACTIVITY_LOGS_MANAGE,
          },
          {
            title: tCommon(($) => $.components.appSidebar.contributions),
            href: ContributionController(),
            icon: FileStackIcon,
            // permissionNeeded: TenantPermission.ACTIVITY_LOGS_MANAGE,
          },
        ],
      },
      {
        heading: tCommon(($) => $.components.appSidebar.communication),
        items: [
          {
            title: tCommon(($) => $.components.appSidebar.emails),
            href: EmailController.index(),
            icon: MailsIcon,
            permissionNeeded: TenantPermission.EMAILS_MANAGE,
          },
        ],
      },
    ],
    [tCommon],
  );

  const footerNavItems: NavItem[] = [
    {
      title: tCommon(($) => $.components.appSidebar.churchSettings),
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
