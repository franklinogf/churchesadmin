import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { usePdfGenerator } from '@/contexts/pdf-generator-context';
import type { ColumnDef } from '@tanstack/react-table';

export function PdfRowsTable<TData>({ data, columns, rowId }: { data: TData[]; columns: ColumnDef<TData>[]; rowId?: keyof TData }) {
  const { setRows } = usePdfGenerator();
  rowId = rowId || ('id' as keyof TData); // Default to 'id' if not provided

  return <DataTable onSelectedRowsChange={setRows} columns={columns} rowId={rowId} data={data} />;
}
