import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { ChartContainer, type ChartConfig } from '@/components/ui/chart';
import { useTranslations } from '@/hooks/use-translations';
import type { ReactElement } from 'react';

interface ChartCardProps {
  children: React.ReactNode & ReactElement;
  chartConfig: ChartConfig;
  title: string;
  description?: string;

  total?: number;
  type?: 'currency' | 'number';
}
export function ChartCard({ children, chartConfig, title, description, total, type = 'number' }: ChartCardProps) {
  const { t } = useTranslations();
  return (
    <Card>
      <CardHeader>
        <CardTitle>{title}</CardTitle>
        {description && <CardDescription>{description}</CardDescription>}
      </CardHeader>
      <CardContent className="min-h-[200px] p-1">
        {total === 0 ? (
          <div className="flex h-full w-full items-center justify-center">
            <p className="text-muted-foreground">{t('No data available')}</p>
          </div>
        ) : (
          <ChartContainer config={chartConfig} className="min-h-[200px] w-full">
            {children}
          </ChartContainer>
        )}
      </CardContent>
      <CardFooter className="py-0">
        {/* the total */}
        {total === 0 ? null : (
          <div className="flex items-center gap-x-2">
            <span className="text-muted-foreground text-sm font-medium">{t('Total')}</span>
            <span className="text-foreground text-lg font-semibold">
              {type === 'currency' ? `$${total?.toLocaleString(undefined, { minimumFractionDigits: 2 })}` : total?.toLocaleString()}
            </span>
          </div>
        )}
      </CardFooter>
    </Card>
  );
}
