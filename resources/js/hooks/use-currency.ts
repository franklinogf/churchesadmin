import { useLaravelReactI18n } from 'laravel-react-i18n';
export function useCurrency() {
  const { currentLocale } = useLaravelReactI18n();

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
