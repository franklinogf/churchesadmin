import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { useCurrency } from '@/hooks/use-currency';
import { useTranslations } from '@/hooks/use-translations';
import type { Expense } from '@/types/models/expense';

export function ViewExpenseModal({ expense, children }: { expense: Expense; children: React.ReactNode }) {
  const { formatCurrency } = useCurrency();
  const { t } = useTranslations();
  return (
    <Dialog>
      <DialogTrigger asChild>{children}</DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>{t('Expense Details')}</DialogTitle>
          <DialogDescription hidden />
        </DialogHeader>

        <div className="space-y-2">
          <p>
            <strong>{t('Wallet')}:</strong> {expense.transaction.wallet?.name}
          </p>
          <p>
            <strong>{t('Expense type')}:</strong> {expense.expenseType.name}
          </p>
          <p>
            <strong>{t('Member')}:</strong> {expense.member ? `${expense.member.name} ${expense.member.lastName}` : 'N/A'}
          </p>
          <p>
            <strong>{t('Amount')}:</strong> {formatCurrency(expense.transaction.amountFloat)}
          </p>
          <p>
            <strong>{t('Date')}:</strong> {new Date(expense.date).toLocaleDateString()}
          </p>
        </div>
      </DialogContent>
    </Dialog>
  );
}
