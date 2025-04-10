import { cn } from '@/lib/utils';
import { AsteriskIcon } from 'lucide-react';

export function RequiredFieldIcon({ className }: { className?: string }) {
    return <AsteriskIcon className={cn('size-3 text-red-600 dark:text-red-400', className)} />;
}
