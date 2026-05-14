import { ChartCard } from '@/components/charts/chart-card';
import { ChartLegend, ChartLegendContent, ChartTooltip, ChartTooltipContent } from '@/components/ui/chart';
import { useMemo } from 'react';
import { useTranslation } from 'react-i18next';
import { Bar, BarChart, CartesianGrid, XAxis, YAxis } from 'recharts';

export type PersonChart = {
  month: string;
  members: number;
  missionaries: number;
  visitors: number;
};

export function PersonsChart({ data }: { data: PersonChart[] }) {
  const { t: tCommon } = useTranslation('common');
  const chartConfig = useMemo(
    () => ({
      members: {
        label: tCommon(($) => $.components.charts.personsChart.members),
        color: 'var(--chart-1)',
      },
      missionaries: {
        label: tCommon(($) => $.components.charts.personsChart.missionaries),
        color: 'var(--chart-2)',
      },
      visitors: {
        label: tCommon(($) => $.components.charts.personsChart.visitors),
        color: 'var(--chart-3)',
      },
    }),
    [tCommon],
  );
  const total = useMemo(() => data.reduce((acc, item) => acc + item.members + item.missionaries + item.visitors, 0), [data]);

  return (
    <ChartCard
      title={tCommon(($) => $.components.charts.personsChart.personsByMonth)}
      chartConfig={chartConfig}
      description={`${tCommon(($) => $.components.charts.personsChart.countPersons, { count: total })}`}
      noData={total === 0}
    >
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
