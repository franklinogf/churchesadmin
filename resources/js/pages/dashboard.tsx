import type { ExpenseChart } from '@/components/charts/expenses-chart';
import ExpensesChart from '@/components/charts/expenses-chart';
import OfferingsChart, { type OfferingChart } from '@/components/charts/offerings-chart';
import { PersonsChart, type PersonChart } from '@/components/charts/persons-chart';
import { WalletsChart } from '@/components/charts/wallets-chart';
import AppLayout from '@/layouts/app-layout';
import { useTranslation } from 'react-i18next';
interface DashboardProps {
  expenses: ExpenseChart[];
  offerings: OfferingChart[];
  persons: PersonChart[];
  wallets: WalletsChart[];
}

export default function Dashboard({ expenses, offerings, persons, wallets }: DashboardProps) {
  const { t: tPages } = useTranslation('pages');
  return (
    <AppLayout breadcrumbs={[{ title: tPages(($) => $.dashboard.dashboard) }]} title={tPages(($) => $.dashboard.dashboard)}>
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
