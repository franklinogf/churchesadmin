import { cn } from '@/lib/utils';

interface MaxWidthSectionProps {
    children: React.ReactNode;
    className?: string;
}
export function MaxWidthSection({ children, className }: MaxWidthSectionProps) {
    return <section className={cn('w-full px-4 py-8 lg:py-16', className)}>{children}</section>;
}
