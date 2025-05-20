import { useTranslations } from '@/hooks/use-translations';
export function useCurrency() {
  const { currentLocale } = useTranslations();

  function formatCurrency(amount: number | string): string {
    const parsedAmount = typeof amount === 'string' ? parseFloat(amount) : amount;
    if (isNaN(parsedAmount)) {
      return '';
    }
    return new Intl.NumberFormat(currentLocale(), { style: 'currency', currency: 'USD' }).format(parsedAmount);
  }

  function toPositive(amount: number | string): string {
    const parsedAmount = typeof amount === 'string' ? parseFloat(amount) : amount;
    if (isNaN(parsedAmount)) {
      return '';
    }
    return Math.abs(parsedAmount).toFixed(2);
  }

  return { formatCurrency, toPositive };
}
