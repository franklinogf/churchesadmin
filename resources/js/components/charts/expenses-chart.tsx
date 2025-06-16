import { ChartCard } from '@/components/charts/chart-card';
import { ChartTooltip, ChartTooltipContent, type ChartConfig } from '@/components/ui/chart';
import { useTranslations } from '@/hooks/use-translations';
import { useMemo } from 'react';
import { Bar, BarChart, CartesianGrid, LabelList, XAxis, YAxis } from 'recharts';
export type ExpenseChart = {
  month: string;
  total: string;
};
const chartConfig = {
  total: {
    label: 'Total',
    color: 'var(--chart-1)',
  },
} satisfies ChartConfig;

export default function ExpensesChart({ data }: { data: ExpenseChart[] }) {
  const { t } = useTranslations();

  const total = useMemo(() => data.reduce((acc, item) => acc + parseFloat(item.total), 0), [data]);

  return (
    <ChartCard
      total={total}
      type="currency"
      noData={total === 0}
      title={t('Expenses by month')}
      chartConfig={chartConfig}
      description="This chart shows the total expenses for each month. The values are in USD."
    >
      <BarChart accessibilityLayer data={data} margin={{ top: 10 }}>
        <CartesianGrid vertical={false} />
        <XAxis dataKey="month" type="category" tickLine={false} tickMargin={10} axisLine={false} tickFormatter={(value) => value.slice(0, 3)} />
        <YAxis dataKey="total" type="number" hide />
        <ChartTooltip cursor={false} content={<ChartTooltipContent indicator="line" />} />
        <Bar dataKey="total" fill="var(--color-total)" radius={2}>
          <LabelList dataKey="total" position="top" offset={8} className="fill-foreground" fontSize={10} formatter={(value: string) => `$${value}`} />
        </Bar>
      </BarChart>
    </ChartCard>
  );
}
