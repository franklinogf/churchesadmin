import { useTranslation } from 'react-i18next';
export function useCurrency() {
  const { i18n } = useTranslation();

  function formatCurrency(amount: number | string): string {
    const parsedAmount = typeof amount === 'string' ? parseFloat(amount) : amount;
    if (isNaN(parsedAmount)) {
      return '';
    }
    return new Intl.NumberFormat(i18n.language, { style: 'currency', currency: 'USD' }).format(parsedAmount);
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
