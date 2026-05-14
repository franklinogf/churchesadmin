import { DatatableCellBoolean as BaseDatatableCellBoolean } from '@/components/datatable/datatable-cell';

export function DatatableCellBoolean({ trueCondition, value }: { trueCondition?: boolean; value?: boolean }) {
  return <BaseDatatableCellBoolean value={value ?? trueCondition ?? false} />;
}
