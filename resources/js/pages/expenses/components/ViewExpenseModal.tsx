import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { useCurrency } from '@/hooks/use-currency';
import type { Expense } from '@/types/models/expense';

export function ViewExpenseModal({ expense, children }: { expense: Expense; children: React.ReactNode }) {
  const { formatCurrency } = useCurrency();
  return (
    <Dialog>
      <DialogTrigger asChild>{children}</DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Expense Details</DialogTitle>
          <DialogDescription hidden />
        </DialogHeader>

        <div className="space-y-2">
          <p>
            <strong>Wallet:</strong> {expense.transaction.wallet?.name}
          </p>
          <p>
            <strong>Expense Type:</strong> {expense.expenseType.name}
          </p>
          <p>
            <strong>Member:</strong> {expense.member ? `${expense.member.name} ${expense.member.lastName}` : 'N/A'}
          </p>
          <p>
            <strong>Amount:</strong> {formatCurrency(expense.transaction.amountFloat)}
          </p>
          <p>
            <strong>Date:</strong> {new Date(expense.date).toLocaleDateString()}
          </p>
        </div>
      </DialogContent>
    </Dialog>
  );
}
