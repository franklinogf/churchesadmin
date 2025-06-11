import { selectionHeader } from '@/components/custom-ui/datatable/columns';
import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { FormErrorList } from '@/components/forms/form-error-list';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { SwitchField } from '@/components/forms/inputs/SwitchField';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import type { SelectOption } from '@/types';
import type { Member } from '@/types/models/member';
import { usePage } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { Loader2Icon } from 'lucide-react';
import { useEffect, useMemo, useState } from 'react';

interface MembersReportProps {
  members: Member[];
  columns: { name: string; label: string; selected: boolean }[];
  formatOptions: SelectOption[];
  orientationOptions: SelectOption[];
}

export default function MembersReport({ members, columns, formatOptions, orientationOptions }: MembersReportProps) {
  const [unSelectedColumns, setUnSelectedColumns] = useState(columns.filter((col) => !col.selected).map((col) => col.name));
  const [selectedFormat, setSelectedFormat] = useState('letter');
  const [selectedOrientation, setSelectedOrientation] = useState('Portrait');
  const [selectedRows, setSelectedRows] = useState<string[]>([]);
  const [isIframeLoading, setIsIframeLoading] = useState(true);
  const [debouncedIframeSrc, setDebouncedIframeSrc] = useState(route('reports.members.pdf'));

  const dataColumns = useMemo<ColumnDef<Member>[]>(
    () => [
      selectionHeader as ColumnDef<Member>,
      {
        enableHiding: false,
        accessorKey: 'name',
        header: 'Name',
        cell: ({ row }) => `${row.original.name} ${row.original.lastName}`,
      },
    ],
    [],
  );

  const rawIframeSrc = useMemo(() => {
    return route('reports.members.pdf', {
      format: selectedFormat,
      orientation: selectedOrientation,
      rows: selectedRows,
      unSelectedColumns: unSelectedColumns,
    });
  }, [selectedFormat, selectedOrientation, selectedRows, unSelectedColumns]);

  useEffect(() => {
    setIsIframeLoading(true);
    const timeout = setTimeout(() => {
      setDebouncedIframeSrc(rawIframeSrc);
    }, 500);

    return () => clearTimeout(timeout);
  }, [rawIframeSrc]);

  const openPdfInNewTab = () => {
    window.open(debouncedIframeSrc);
  };

  return (
    <AppLayout title="Members Report" breadcrumbs={[{ title: 'Reports', href: route('reports') }, { title: 'Members Report' }]}>
      <PageTitle>Members Report</PageTitle>
      <FormErrorList errors={usePage().props.errors} />
      <div className="mb-1 flex justify-end">
        <Button disabled={isIframeLoading} size="sm" onClick={openPdfInNewTab}>
          Open in new tab
        </Button>
      </div>
      <section className="grid h-[400px] grid-cols-1 gap-4 md:grid-cols-2">
        <Card>
          <CardContent>
            <div className="mb-4 space-y-4">
              <div className="flex max-w-sm gap-4">
                <SelectField
                  className="grow"
                  label={'Format'}
                  value={selectedFormat}
                  options={formatOptions}
                  onChange={(value) => {
                    setSelectedFormat(value);
                  }}
                />
                <SelectField
                  className="grow"
                  label={'Orientation'}
                  value={selectedOrientation}
                  options={orientationOptions}
                  onChange={(value) => {
                    setSelectedOrientation(value);
                  }}
                />
              </div>

              <div>
                <h2 className="text-lg font-semibold">Columns to show on the pdf</h2>
                <div className="grid grid-cols-1 gap-2 overflow-y-auto md:grid-cols-2">
                  {columns.map((col) => (
                    <SwitchField
                      label={col.label}
                      key={col.name}
                      value={!unSelectedColumns.includes(col.name)}
                      onChange={(value) => {
                        setUnSelectedColumns((prev) => {
                          if (value) {
                            return prev.filter((c) => c !== col.name);
                          }
                          return [...prev, col.name];
                        });
                      }}
                    />
                  ))}
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        <div className="relative h-full w-full rounded-lg border">
          {isIframeLoading && (
            <div className="absolute inset-0 z-10 flex flex-col items-center justify-center rounded-lg bg-white/90">
              <Loader2Icon className="animate-spin text-gray-500" size={24} />
              <span className="text-gray-600">Loading preview</span>
            </div>
          )}
          <iframe className="h-full w-full rounded-lg border" src={debouncedIframeSrc} onLoad={() => setIsIframeLoading(false)} />
        </div>
      </section>
      <DataTable onSelectedRowsChange={setSelectedRows} print={openPdfInNewTab} columns={dataColumns} rowId="id" data={members} />
    </AppLayout>
  );
}
