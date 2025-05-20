import { CheckIcon, XCircleIcon } from 'lucide-react';
import { DatatableCell } from './DatatableCell';

export function DatatableCellBoolean({ trueCondition: value }: { trueCondition: boolean }) {
  return (
    <DatatableCell justify="center">
      {value ? <CheckIcon className="size-4 text-green-600" /> : <XCircleIcon className="text-destructive size-4" />}
    </DatatableCell>
  );
}
