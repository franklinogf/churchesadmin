import {
  DatatableCell as BaseDatatableCell,
  DatatableCellBoolean as BaseDatatableCellBoolean,
  DatatableCellBadge,
} from '@/components/datatable/datatable-cell';

type Alignment = 'start' | 'center' | 'end';

type DatatableCellProps = {
  children: React.ReactNode;
  className?: string;
  justify?: Alignment;
  align?: Alignment;
};

export function DatatableCell({ children, className, justify, align }: DatatableCellProps) {
  return (
    <BaseDatatableCell align={align ?? justify} className={className}>
      {children}
    </BaseDatatableCell>
  );
}

export function DatatableBadgeCell({
  children,
  className,
  variant,
}: {
  children: React.ReactNode;
  className?: string;
  variant?: React.ComponentProps<typeof DatatableCellBadge>['variant'];
}) {
  return (
    <DatatableCellBadge className={className} variant={variant}>
      {children}
    </DatatableCellBadge>
  );
}

export { DatatableCellBadge };

export function DatatableCellBoolean({ trueCondition, value }: { trueCondition?: boolean; value?: boolean }) {
  return <BaseDatatableCellBoolean value={value ?? trueCondition ?? false} />;
}
