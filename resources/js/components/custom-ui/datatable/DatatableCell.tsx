import { Badge } from '@/components/ui/badge';
import { cva, type VariantProps } from 'class-variance-authority';

export const cellVariants = cva('flex items-center', {
  variants: {
    justify: {
      start: 'justify-start',
      end: 'justify-end',
      center: 'justify-center',
    },
  },
  defaultVariants: {
    justify: 'start',
  },
});

interface DatatableCellCenterProps extends VariantProps<typeof cellVariants> {
  children: React.ReactNode;
  className?: string;
}

export function DatatableCell({ children, justify = 'start', className }: DatatableCellCenterProps) {
  return <div className={cellVariants({ justify, className })}>{children}</div>;
}

export function DatatableBadgeCell({
  children,
  variant = 'secondary',
  className,
}: {
  children: React.ReactNode;
  variant?: VariantProps<typeof Badge>['variant'];
  className?: string;
}) {
  return (
    <DatatableCell justify="center">
      <Badge className={className} variant={variant}>
        {children}
      </Badge>
    </DatatableCell>
  );
}
