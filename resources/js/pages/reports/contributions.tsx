import { selectionHeader } from '@/components/custom-ui/datatable/columns';
import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { DataTableColumnHeader } from '@/components/custom-ui/datatable/DataTableColumnHeader';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { SessionName } from '@/enums/SessionName';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import type { SelectOption } from '@/types';
import type { CurrentYear } from '@/types/models/current-year';
import { router } from '@inertiajs/react';
import type { ColumnDef } from '@tanstack/react-table';
import { MailIcon, PrinterIcon } from 'lucide-react';
import { useState } from 'react';

type ContributionsRecord = {
  id: number;
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
  const [selectedContributions, setSelectedContributions] = useState<string[]>([]);

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

  const handlePrintPdf = () => {
    const url = route('reports.contributions.pdf.multiple', {
      year: year.year,
      members: selectedContributions,
    });
    window.open(url, '_blank');
  };

  const handleSendEmail = () => {
    router.post(
      route('session', {
        name: SessionName.CONTRIBUTIONS_REPORT_YEAR,
        value: {
          member_ids: selectedContributions,
        },
        redirect_to: 'communication.emails.create',
      }),
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

      <div className="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div className="flex-1 sm:max-w-xs">
          <SelectField label={t('Fiscal Year')} value={year.year} onChange={handleYearChange} options={years} />
        </div>

        <div className="flex gap-2">
          <Button variant="outline" size="sm" disabled={selectedContributions.length === 0 || year.isCurrent} onClick={handlePrintPdf}>
            <PrinterIcon className="size-4" />
            {t('Export PDF')}
          </Button>
          <Button variant="outline" size="sm" disabled={selectedContributions.length === 0 || year.isCurrent} onClick={handleSendEmail}>
            <MailIcon className="size-4" />
            {t('Send Email')}
          </Button>
        </div>
      </div>

      <DataTable data={contributions} columns={dataColumns} rowId="id" onSelectedRowsChange={setSelectedContributions} />
    </AppLayout>
  );
}
