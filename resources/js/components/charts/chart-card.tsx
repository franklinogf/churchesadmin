import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ChartContainer, type ChartConfig } from '@/components/ui/chart';
import { useTranslations } from '@/hooks/use-translations';
import type { ReactElement } from 'react';

interface ChartCardProps {
  children: React.ReactNode & ReactElement;
  chartConfig: ChartConfig;
  title: string;
  description?: string;
  noData?: boolean;
}
export function ChartCard({ children, chartConfig, title, description, noData }: ChartCardProps) {
  const { t } = useTranslations();
  return (
    <Card>
      <CardHeader>
        <CardTitle>{title}</CardTitle>
        {description && <CardDescription>{description}</CardDescription>}
      </CardHeader>
      <CardContent className="min-h-[200px] p-1">
        {noData ? (
          <div className="flex h-full w-full items-center justify-center">
            <p className="text-muted-foreground">{t('No data available')}</p>
          </div>
        ) : (
          <ChartContainer config={chartConfig} className="min-h-[200px] w-full">
            {children}
          </ChartContainer>
        )}
      </CardContent>
    </Card>
  );
}
