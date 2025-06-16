import { ChartCard } from '@/components/charts/chart-card';
import { ChartTooltip, ChartTooltipContent, type ChartConfig } from '@/components/ui/chart';
import { useTranslations } from '@/hooks/use-translations';
import { Bar, BarChart, CartesianGrid, LabelList, XAxis, YAxis } from 'recharts';
export type OfferingChart = {
  month: string;
  total: string;
};
export const chartConfig = {
  total: {
    label: 'Total',
    color: 'var(--chart-1)',
  },
} satisfies ChartConfig;

export default function OfferingsChart({ data }: { data: OfferingChart[] }) {
  const { t } = useTranslations();
  return (
    <ChartCard noData={data.length === 0} title={t('Offerings by month')} chartConfig={chartConfig}>
      <BarChart accessibilityLayer data={data} margin={{ top: 10 }}>
        <CartesianGrid vertical={false} />
        <XAxis dataKey="month" type="category" tickLine={false} tickMargin={10} axisLine={false} tickFormatter={(value) => value.slice(0, 3)} />
        <YAxis dataKey="total" type="number" hide />
        <ChartTooltip cursor={false} content={<ChartTooltipContent indicator="line" />} />
        <Bar dataKey="total" fill="var(--color-total)" radius={2}>
          <LabelList
            dataKey="total"
            position="top"
            offset={8}
            className="fill-foreground"
            fontSize={10}
            formatter={(value: string) => (value ? `$${value}` : '')}
          />
        </Bar>
      </BarChart>
    </ChartCard>
  );
}
