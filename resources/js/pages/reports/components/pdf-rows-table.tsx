import Datatable, { type StringOrNumberKeyOf } from '@/components/datatable/datatable';
import { usePdfGenerator } from '@/contexts/pdf-generator-context';
import type { ColumnDef } from '@tanstack/react-table';

export function PdfRowsTable<TData>({ data, columns, rowId }: { data: TData[]; columns: ColumnDef<TData>[]; rowId?: StringOrNumberKeyOf<TData> }) {
  const { setRows } = usePdfGenerator();
  const resolvedRowId = rowId || ('id' as StringOrNumberKeyOf<TData>);

  return <Datatable onSelectedRowsChange={setRows} columns={columns} rowId={resolvedRowId} data={data} />;
}
