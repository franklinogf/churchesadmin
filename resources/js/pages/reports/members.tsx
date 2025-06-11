import { selectionHeader } from '@/components/custom-ui/datatable/columns';
import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { FormErrorList } from '@/components/forms/form-error-list';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { SwitchField } from '@/components/forms/inputs/SwitchField';
import { PageTitle } from '@/components/PageTitle';
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import type { SelectOption } from '@/types';
import type { Member } from '@/types/models/member';
import { usePage } from '@inertiajs/react';
import { type ColumnDef } from '@tanstack/react-table';
import { useMemo, useState } from 'react';

interface MembersReportProps {
  members: Member[];
  columns: Array<{ name: string; label: string; selected: boolean }>;
  formatOptions: SelectOption[];
  orientationOptions: SelectOption[];
}

export default function MembersReport({ members, columns, formatOptions, orientationOptions }: MembersReportProps) {
  const [unSelectedColumns, setUnSelectedColumns] = useState(columns.filter((col) => !col.selected).map((col) => col.name));
  const [selectedFormat, setSelectedFormat] = useState('letter');
  const [selectedOrientation, setSelectedOrientation] = useState('Portrait');
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
  const handlePrint = (selectedRows: string[]) => {
    window.open(
      route('reports.members.pdf', {
        format: selectedFormat,
        orientation: selectedOrientation,
        rows: selectedRows,
        withoutColumns: unSelectedColumns,
      }),
    );
  };

  return (
    <AppLayout title="Members Report" breadcrumbs={[{ title: 'Reports', href: route('reports') }, { title: 'Members Report' }]}>
      <PageTitle>Members Report</PageTitle>
      <FormErrorList errors={usePage().props.errors} />
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
              <div className="flex flex-wrap gap-4">
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
      <DataTable print={handlePrint} columns={dataColumns} rowId="id" data={members} />
    </AppLayout>
  );
}
