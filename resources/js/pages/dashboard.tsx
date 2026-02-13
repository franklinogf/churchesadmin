import type { ExpenseChart } from '@/components/charts/expenses-chart';
import ExpensesChart from '@/components/charts/expenses-chart';
import OfferingsChart, { type OfferingChart } from '@/components/charts/offerings-chart';
import { PersonsChart, type PersonChart } from '@/components/charts/persons-chart';
import { WalletsChart } from '@/components/charts/wallets-chart';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
interface DashboardProps {
  expenses: ExpenseChart[];
  offerings: OfferingChart[];
  persons: PersonChart[];
  wallets: WalletsChart[];
}

export default function Dashboard({ expenses, offerings, persons, wallets }: DashboardProps) {
  const { t } = useTranslations();
  return (
    <AppLayout breadcrumbs={[{ title: t('Dashboard') }]} title={t('Dashboard')}>
      <div className="flex h-full flex-1 flex-col gap-4 rounded-xl">
        <div className="grid auto-rows-min gap-4 md:grid-cols-3">
          <ExpensesChart data={expenses} />
          <OfferingsChart data={offerings} />
          <PersonsChart data={persons} />
        </div>
        <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 overflow-hidden rounded-xl border md:min-h-min">
          <WalletsChart data={wallets} />
        </div>
      </div>
    </AppLayout>
  );
}
