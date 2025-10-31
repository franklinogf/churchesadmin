import { selectionHeader } from '@/components/custom-ui/datatable/columns';
import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import type { SelectOption } from '@/types';
import type { CurrentYear } from '@/types/models/current-year';
import { router } from '@inertiajs/react';
import type { ColumnDef } from '@tanstack/react-table';

type ContributionsRecord = {
  name: string;
  email: string;
  contributionAmount: number;
};

interface ContributionsPageProps {
  contributions: ContributionsRecord[];
  year: CurrentYear;
  years: SelectOption[];
}

export default function ContributionsPage({ contributions, year, years }: ContributionsPageProps) {
  const { t } = useTranslations();

  const dataColumns: ColumnDef<ContributionsRecord>[] = [
    selectionHeader as ColumnDef<ContributionsRecord>,
    {
      enableHiding: false,
      accessorKey: 'name',
      header: ({ column }) => <DataTableColumnHeader column={column} title="Name" />,
    },
    {
      accessorKey: 'email',
      header: ({ column }) => <DataTableColumnHeader column={column} title="Email" />,
    },
    {
      accessorKey: 'contributionAmount',
      header: ({ column }) => <DataTableColumnHeader column={column} title="Contribution" />,
      cell: ({ row }) => <DatatableCell justify="end">{row.original.contributionAmount}</DatatableCell>,
    },
  ];

  const handleYearChange = (year: string) => {
    router.get(
      route('reports.contributions'),
      { year },
      {
        preserveState: true,
        replace: true,
      },
    );
  };

  return (
    <AppLayout title={t('Contributions')} breadcrumbs={[{ title: t('Reports'), href: route('reports') }, { title: t('Contributions') }]}>
      <PageTitle description={t('Contributions of the fiscal year :year', { year: year.year })}>{t('Contributions')}</PageTitle>
      <small className="text-muted-foreground text-center">
        ({year.startDate} - {year.endDate})
      </small>

      {year.isCurrent && (
        <Alert className="text-muted-foreground my-4" variant="warning">
          <AlertDescription>
            {t(
              'The selected year is the current fiscal year, is not allowed to send reports for the current year. Please select a previous fiscal year to view contributions.',
            )}
          </AlertDescription>
        </Alert>
      )}

      <div className="mb-6">
        <SelectField label={t('Fiscal Year')} value={year.year} onChange={handleYearChange} options={years} />
      </div>

      <DataTable data={contributions} columns={dataColumns} />
    </AppLayout>
  );
}
