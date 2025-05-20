import { cva, type VariantProps } from 'class-variance-authority';

const cellVariants = cva('flex items-center', {
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
