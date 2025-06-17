import { ChartContainer, ChartTooltip, ChartTooltipContent } from '@/components/ui/chart';
import { useTranslations } from '@/hooks/use-translations';
import { useMemo, useState } from 'react';
import { Bar, BarChart, CartesianGrid, XAxis, YAxis } from 'recharts';
import { Button } from '../ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '../ui/card';

export type WalletsChart = {
  month: string;
  wallet: string;
  deposits: number;
  withdrawals: number;
};

export function WalletsChart({ data }: { data: WalletsChart[] }) {
  const wallets = new Set(data.map((item) => item.wallet));
  const [selectedWallet, setSelectedWallet] = useState<string | null>([...wallets][0] || null);
  const { t } = useTranslations();
  const chartConfig = useMemo(
    () => ({
      deposits: {
        color: 'var(--chart-1)',
      },
      withdrawals: {
        color: 'var(--chart-2)',
      },
    }),
    [],
  );

  //group by wallet
  const pivotedData = Array.from(new Set(data.map((d) => d.month))).map((month) => {
    const entry = { month };
    wallets.forEach((wallet) => {
      const found = data.find((d) => d.month === month && d.wallet === wallet);
      Object.assign(entry, {
        [`${wallet}_deposits`]: found?.deposits || 0,
        [`${wallet}_withdrawals`]: found?.withdrawals || 0,
      });
    });
    return entry;
  });

  return (
    <Card>
      <CardHeader>
        <CardTitle>{t('Wallets by month')}</CardTitle>
      </CardHeader>
      <CardContent>
        <div className="mb-4 flex flex-wrap items-center justify-start">
          {[...wallets].map((wallet) => (
            <Button
              key={wallet}
              variant={selectedWallet === wallet ? 'default' : 'outline'}
              className="mr-2 mb-2"
              onClick={() => setSelectedWallet(wallet)}
            >
              {wallet.charAt(0).toUpperCase() + wallet.slice(1)}
            </Button>
          ))}
        </div>
        <ChartContainer config={chartConfig} className="max-h-[300px] min-h-[200px] w-full">
          <BarChart accessibilityLayer data={pivotedData}>
            <CartesianGrid vertical={false} />
            <YAxis type="number" domain={[0, (dataMax: number) => Math.ceil(dataMax * 1.1)]} hide />
            <XAxis type="category" dataKey="month" tickLine={false} tickMargin={10} axisLine={false} tickFormatter={(value) => value.slice(0, 3)} />
            <ChartTooltip cursor={false} content={<ChartTooltipContent indicator="line" />} />
            <Bar
              name={`${t('enum.transaction_type.deposit')}`}
              dataKey={`${selectedWallet}_deposits`}
              fill={'var(--color-deposits)'}
              radius={[0, 0, 2, 2]}
              stackId="a"
            />
            <Bar
              dataKey={`${selectedWallet}_withdrawals`}
              fill={'var(--color-withdrawals)'}
              radius={[2, 2, 0, 0]}
              stackId="a"
              name={`${t('enum.transaction_type.withdraw')}`}
            />
          </BarChart>
        </ChartContainer>
      </CardContent>
    </Card>
  );
}
