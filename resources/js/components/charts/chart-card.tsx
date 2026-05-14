import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ChartContainer, type ChartConfig } from '@/components/ui/chart';
import { cn } from '@/lib/utils';
import type { ReactElement } from 'react';
import { useTranslation } from 'react-i18next';

interface ChartCardProps {
  children: React.ReactNode & ReactElement;
  chartConfig: ChartConfig;
  title: string;
  description?: string;
  noData?: boolean;
  chartClassName?: string;
}
export function ChartCard({ children, chartConfig, title, description, noData, chartClassName }: ChartCardProps) {
  const { t: tCommon } = useTranslation('common');
  return (
    <Card>
      <CardHeader>
        <CardTitle>{title}</CardTitle>
        {description && <CardDescription>{description}</CardDescription>}
      </CardHeader>
      <CardContent className="h-full min-h-[200px] p-1">
        {noData ? (
          <div className="flex h-full w-full items-center justify-center">
            <p className="text-muted-foreground">{tCommon(($) => $.components.charts.chartCard.noDataAvailable)}</p>
          </div>
        ) : (
          <ChartContainer config={chartConfig} className={cn('min-h-[200px] w-full', chartClassName)}>
            {children}
          </ChartContainer>
        )}
      </CardContent>
    </Card>
  );
}
