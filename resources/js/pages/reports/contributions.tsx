import ContributionController from '@/actions/App/Http/Controllers/Pdf/ContributionController';
import ContributionPdfController from '@/actions/App/Http/Controllers/Pdf/ContributionPdfController';
import ReportController from '@/actions/App/Http/Controllers/ReportController';
import { DatatableCell } from '@/components/custom-ui/datatable/DatatableCell';
import { selectionHeader } from '@/components/datatable/columns';
import Datatable from '@/components/datatable/datatable';
import { DatatableHeader } from '@/components/datatable/datatable-header';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import type { SelectOption } from '@/types';
import type { CurrentYear } from '@/types/models/current-year';
import { router } from '@inertiajs/react';
import type { ColumnDef } from '@tanstack/react-table';
import { MailIcon, PrinterIcon } from 'lucide-react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';

type ContributionsRecord = {
  id: number;
  name: string;
  email: string;
  contributionAmount: number;
};

interface ContributionsPageProps {
  contributions: ContributionsRecord[];
  year: CurrentYear | null;
  years: SelectOption[];
}

export default function ContributionsPage({ contributions, year, years }: ContributionsPageProps) {
  const { t: tPages } = useTranslation('pages');
  const [selectedContributions, setSelectedContributions] = useState<string[]>([]);
  const [isLoading, setIsLoading] = useState(false);

  const dataColumns: ColumnDef<ContributionsRecord>[] = [
    selectionHeader as ColumnDef<ContributionsRecord>,
    {
      enableHiding: false,
      accessorKey: 'name',
      header: ({ column }) => <DatatableHeader column={column} title="Name" />,
    },
    {
      accessorKey: 'email',
      header: ({ column }) => <DatatableHeader column={column} title="Email" />,
    },
    {
      accessorKey: 'contributionAmount',
      header: ({ column }) => <DatatableHeader column={column} title="Contribution" />,
      cell: ({ row }) => <DatatableCell justify="end">{row.original.contributionAmount}</DatatableCell>,
    },
  ];

  const handleYearChange = (year: string) => {
    router.visit(ContributionController({ query: { year } }), {
      preserveState: true,
      replace: true,
    });
  };

  const handlePrintPdf = () => {
    const url = ContributionPdfController.multiple({ query: { year: year?.year, memberIds: selectedContributions } }).url;
    window.open(url, '_blank');
  };

  const handleSendEmail = () => {
    router.visit(ContributionPdfController.email({ query: { year: year?.year, memberIds: selectedContributions } }), {
      onStart: () => setIsLoading(true),
      onFinish: () => setIsLoading(false),
    });
  };

  if (years.length === 0) {
    return (
      <AppLayout
        title={tPages(($) => $.reports.contributions.contributions)}
        breadcrumbs={[
          { title: tPages(($) => $.reports.contributions.reports), href: ReportController().url },
          { title: tPages(($) => $.reports.contributions.contributions) },
        ]}
      >
        <PageTitle>{tPages(($) => $.reports.contributions.contributions)}</PageTitle>
        <Alert className="text-muted-foreground my-4" variant="warning">
          <AlertDescription>{tPages(($) => $.reports.contributions.noClosedFiscalYearsFoundPleaseCloseAFiscal)}</AlertDescription>
        </Alert>
      </AppLayout>
    );
  }
  return (
    <AppLayout
      title={tPages(($) => $.reports.contributions.contributions)}
      breadcrumbs={[
        { title: tPages(($) => $.reports.contributions.reports), href: ReportController().url },
        { title: tPages(($) => $.reports.contributions.contributions) },
      ]}
    >
      <PageTitle description={tPages(($) => $.reports.contributions.contributionsOfTheFiscalYearYear, { year: year?.year || '' })}>
        {tPages(($) => $.reports.contributions.contributions)}
      </PageTitle>
      <small className="text-muted-foreground text-center">
        ({year?.startDate} - {year?.endDate})
      </small>

      {year?.isCurrent && (
        <Alert className="text-muted-foreground my-4" variant="warning">
          <AlertDescription>{tPages(($) => $.reports.contributions.theSelectedYearIsTheCurrentFiscalYearIs)}</AlertDescription>
        </Alert>
      )}

      <div className="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div className="flex-1 sm:max-w-xs">
          <SelectField
            label={tPages(($) => $.reports.contributions.fiscalYear)}
            value={year?.year}
            onValueChange={handleYearChange}
            options={years ?? []}
          />
        </div>

        <div className="flex gap-2">
          <Button variant="outline" size="sm" disabled={isLoading || selectedContributions.length === 0 || year?.isCurrent} onClick={handlePrintPdf}>
            <PrinterIcon className="size-4" />
            {tPages(($) => $.reports.contributions.exportPdf)}
          </Button>
          <Button variant="outline" size="sm" disabled={isLoading || selectedContributions.length === 0 || year?.isCurrent} onClick={handleSendEmail}>
            <MailIcon className="size-4" />
            {tPages(($) => $.reports.contributions.sendEmail)}
          </Button>
        </div>
      </div>

      <Datatable data={contributions ?? []} columns={dataColumns} rowId="id" onSelectedRowsChange={setSelectedContributions} />
    </AppLayout>
  );
}
