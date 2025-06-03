import type { Row } from '@tanstack/react-table';

export const sortDate = <TData>(rowA: Row<TData>, rowB: Row<TData>, columnId: string): number => {
  const a = (rowA.original as Record<string, unknown>)[columnId] as string | null;
  const b = (rowB.original as Record<string, unknown>)[columnId] as string | null;

  if (!a && !b) return 0;
  if (!a) return 1; // nulls last
  if (!b) return -1;

  // Compare as dates
  return new Date(a).getTime() - new Date(b).getTime();
};
