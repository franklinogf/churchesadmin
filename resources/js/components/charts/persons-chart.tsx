import { ChartCard } from '@/components/charts/chart-card';
import { ChartLegend, ChartLegendContent, ChartTooltip, ChartTooltipContent } from '@/components/ui/chart';
import { useTranslations } from '@/hooks/use-translations';
import { useMemo } from 'react';
import { Bar, BarChart, CartesianGrid, XAxis, YAxis } from 'recharts';

export type PersonChart = {
  month: string;
  members: number;
  missionaries: number;
  visitors: number;
};

export function PersonsChart({ data }: { data: PersonChart[] }) {
  const { t } = useTranslations();
  const chartConfig = useMemo(
    () => ({
      members: {
        label: t('Members'),
        color: 'var(--chart-1)',
      },
      missionaries: {
        label: t('Missionaries'),
        color: 'var(--chart-2)',
      },
      visitors: {
        label: t('Visitors'),
        color: 'var(--chart-3)',
      },
    }),
    [t],
  );
  const total = useMemo(() => data.reduce((acc, item) => acc + item.members + item.missionaries + item.visitors, 0), [data]);

  return (
    <ChartCard title={t('Persons by month')} chartConfig={chartConfig} description={`${t(':count persons', { count: total })}`} noData={total === 0}>
      <BarChart accessibilityLayer data={data}>
        <CartesianGrid vertical={false} />
        <YAxis domain={[0, (dataMax: number) => Math.ceil(dataMax * 1.1)]} type="number" hide />
        <XAxis type="category" dataKey="month" tickLine={false} tickMargin={10} axisLine={false} tickFormatter={(value) => value.slice(0, 3)} />
        <ChartTooltip cursor={false} content={<ChartTooltipContent indicator="line" />} />
        <ChartLegend verticalAlign="top" content={<ChartLegendContent />} />

        <Bar dataKey="members" fill="var(--color-members)" radius={2} />
        <Bar dataKey="missionaries" fill="var(--color-missionaries)" radius={2} />
        <Bar dataKey="visitors" fill="var(--color-visitors)" radius={2} />
      </BarChart>
    </ChartCard>
  );
}
