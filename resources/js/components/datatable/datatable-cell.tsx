import type { VariantProps } from 'class-variance-authority';
import { cva } from 'class-variance-authority';
import { CheckIcon, XCircleIcon } from 'lucide-react';
import type { ReactElement } from 'react';

import { Badge } from '@/components/ui/badge';

export const cellVariants = cva('flex items-center text-wrap lg:text-nowrap', {
  variants: {
    align: {
      start: 'justify-start',
      end: 'justify-end',
      center: 'justify-center',
    },
  },
  defaultVariants: {
    align: 'start',
  },
});

interface DatatableCellCenterProps extends VariantProps<typeof cellVariants> {
  children: React.ReactNode;
  className?: string;
}

export function DatatableCell({ children, align = 'start', className }: DatatableCellCenterProps) {
  return <div className={cellVariants({ align, className })}>{children}</div>;
}

export function DatatableCellBadge({
  children,
  variant = 'secondary',
  className,
}: {
  children: React.ReactNode;
  variant?: VariantProps<typeof Badge>['variant'];
  className?: string;
}) {
  return (
    <DatatableCell align="center">
      <Badge className={className} variant={variant}>
        {children}
      </Badge>
    </DatatableCell>
  );
}

interface DatatableBooleanCellProps {
  value: boolean;
  trueIcon?: ReactElement;
  falseIcon?: ReactElement;
}

export function DatatableCellBoolean({ value, trueIcon, falseIcon }: DatatableBooleanCellProps) {
  return (
    <DatatableCell align="center">
      {value ? (trueIcon ?? <CheckIcon className="size-4 text-green-600" />) : (falseIcon ?? <XCircleIcon className="text-destructive size-4" />)}
    </DatatableCell>
  );
}
