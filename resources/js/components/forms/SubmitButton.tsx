import { LoadingTextSwap } from '@/components/LoadingTextSwap';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
interface SubmitButtonProps extends React.ComponentProps<typeof Button> {
  children: React.ReactNode;
  loadingIcon?: React.ReactNode;
  isSubmitting?: boolean;
}
export function SubmitButton({ children, isSubmitting = false, className, loadingIcon, disabled, ...props }: SubmitButtonProps) {
  return (
    <Button
      className={cn('cursor-pointer', className, {
        'cursor-progress disabled:pointer-events-auto': isSubmitting,
      })}
      disabled={isSubmitting || disabled}
      {...props}
      type="submit"
    >
      <LoadingTextSwap loadingIcon={loadingIcon} isLoading={isSubmitting}>
        {children}
      </LoadingTextSwap>
    </Button>
  );
}
