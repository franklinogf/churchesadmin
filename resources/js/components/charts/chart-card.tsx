import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ChartContainer, type ChartConfig } from '@/components/ui/chart';
import { useTranslations } from '@/hooks/use-translations';
import { cn } from '@/lib/utils';
import type { ReactElement } from 'react';

interface ChartCardProps {
  children: React.ReactNode & ReactElement;
  chartConfig: ChartConfig;
  title: string;
  description?: string;
  noData?: boolean;
  chartClassName?: string;
}
export function ChartCard({ children, chartConfig, title, description, noData, chartClassName }: ChartCardProps) {
  const { t } = useTranslations();
  return (
    <Card>
      <CardHeader>
        <CardTitle>{title}</CardTitle>
        {description && <CardDescription>{description}</CardDescription>}
      </CardHeader>
      <CardContent className="h-full min-h-[200px] p-1">
        {noData ? (
          <div className="flex h-full w-full items-center justify-center">
            <p className="text-muted-foreground">{t('No data available')}</p>
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
