import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { useCurrency } from '@/hooks/use-currency';
import type { Expense } from '@/types/models/expense';
import { useTranslation } from 'react-i18next';

export function ViewExpenseModal({ expense, children }: { expense: Expense; children: React.ReactNode }) {
  const { formatCurrency } = useCurrency();
  const { t: tPages } = useTranslation('pages');
  return (
    <Dialog>
      <DialogTrigger asChild>{children}</DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>{tPages(($) => $.expenses.components.ViewExpenseModal.expenseDetails)}</DialogTitle>
          <DialogDescription hidden />
        </DialogHeader>

        <div className="space-y-2">
          <p>
            <strong>{tPages(($) => $.expenses.components.ViewExpenseModal.wallet)}:</strong> {expense.transaction.wallet?.name}
          </p>
          <p>
            <strong>{tPages(($) => $.expenses.components.ViewExpenseModal.expenseType)}:</strong> {expense.expenseType.name}
          </p>
          <p>
            <strong>{tPages(($) => $.expenses.components.ViewExpenseModal.member)}:</strong>{' '}
            {expense.member ? `${expense.member.name} ${expense.member.lastName}` : 'N/A'}
          </p>
          <p>
            <strong>{tPages(($) => $.expenses.components.ViewExpenseModal.amount)}:</strong> {formatCurrency(expense.transaction.amountFloat)}
          </p>
          <p>
            <strong>{tPages(($) => $.expenses.components.ViewExpenseModal.date)}:</strong> {new Date(expense.date).toLocaleDateString()}
          </p>
        </div>
      </DialogContent>
    </Dialog>
  );
}
