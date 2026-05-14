import { DatatableCellActions } from '@/components/datatable/datatable-cell-actions';

export function DatatableActionsDropdown({ children }: { children: React.ReactNode }) {
  return <DatatableCellActions>{children}</DatatableCellActions>;
}
