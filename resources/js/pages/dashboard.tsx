import type { ExpenseChart } from '@/components/charts/expenses-chart';
import ExpensesChart from '@/components/charts/expenses-chart';
import OfferingsChart, { type OfferingChart } from '@/components/charts/offerings-chart';
import { PersonsChart, type PersonChart } from '@/components/charts/persons-chart';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
interface DashboardProps {
  expenses: ExpenseChart[];
  offerings: OfferingChart[];
  persons: PersonChart[];
}

export default function Dashboard({ expenses, offerings, persons }: DashboardProps) {
  const { t } = useTranslations();
  return (
    <AppLayout breadcrumbs={[{ title: t('Dashboard') }]} title={t('Dashboard')}>
      <div className="flex h-full flex-1 flex-col gap-4 rounded-xl">
        <div className="grid auto-rows-min gap-4 md:grid-cols-3">
          <ExpensesChart data={expenses} />
          <OfferingsChart data={offerings} />
          <PersonsChart data={persons} />
        </div>
        <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border md:min-h-min">
          <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
        </div>
      </div>
    </AppLayout>
  );
}
