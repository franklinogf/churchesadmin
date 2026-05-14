import ActivityLogPdfController from '@/actions/App/Http/Controllers/Pdf/ActivityLogPdfController';
import ReportController from '@/actions/App/Http/Controllers/ReportController';
import Datatable from '@/components/datatable/datatable';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import type { ActivityLog } from '@/types/models/activity-log';
import { router } from '@inertiajs/react';
import { FileText, X } from 'lucide-react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { columns } from './includes/columns';
interface IndexProps {
  activityLogs: ActivityLog[];
  logNames: string[];
  filters: {
    log_name?: string;
    start_date?: string;
    end_date?: string;
  };
}
const INITIAL_FILTERS = {
  log_name: 'all',
  start_date: '',
  end_date: '',
} as const;

export default function Index({ activityLogs, logNames, filters }: IndexProps) {
  const { t: tPages } = useTranslation('pages');
  const [startDate, setStartDate] = useState<string>(filters.start_date || INITIAL_FILTERS.start_date);
  const [endDate, setEndDate] = useState<string>(filters.end_date || INITIAL_FILTERS.end_date);
  const [selectedLogName, setSelectedLogName] = useState<string>(filters.log_name || INITIAL_FILTERS.log_name);

  const breadcrumbs: BreadcrumbItem[] = [
    { title: tPages(($) => $.activityLogs.index.reports), href: ReportController().url },
    { title: tPages(($) => $.activityLogs.index.activityLogs) },
  ];

  const applyFilters = () => {
    const params: Record<string, string> = {};

    if (selectedLogName && selectedLogName !== 'all') {
      params.log_name = selectedLogName;
    }

    if (startDate) {
      params.start_date = startDate;
    }

    if (endDate) {
      params.end_date = endDate;
    }

    router.visit(ActivityLogPdfController.index({ query: params }), {
      preserveState: true,
      replace: true,
    });
  };

  const clearFilters = () => {
    setSelectedLogName(INITIAL_FILTERS.log_name);
    setStartDate(INITIAL_FILTERS.start_date);
    setEndDate(INITIAL_FILTERS.end_date);

    router.visit(ActivityLogPdfController.index(), {
      preserveState: true,
      replace: true,
    });
  };

  const handleExportPdf = () => {
    const params: Record<string, string> = {};

    if (selectedLogName) {
      params.log_name = selectedLogName;
    }

    if (startDate) {
      params.start_date = startDate;
    }

    if (endDate) {
      params.end_date = endDate;
    }

    const exportUrl = ActivityLogPdfController.show({ query: params }).url;
    window.open(exportUrl, '_blank');
  };

  const hasActiveFilters =
    selectedLogName !== INITIAL_FILTERS.log_name || startDate !== INITIAL_FILTERS.start_date || endDate !== INITIAL_FILTERS.end_date;

  return (
    <AppLayout breadcrumbs={breadcrumbs} title={tPages(($) => $.activityLogs.index.reports)}>
      <PageTitle>{tPages(($) => $.activityLogs.index.activityLogs)}</PageTitle>

      <Card className="mb-6">
        <CardHeader>
          <CardTitle className="text-lg">{tPages(($) => $.activityLogs.index.general)}</CardTitle>
        </CardHeader>
        <CardContent className="space-y-4">
          <div className="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div>
              <label className="mb-2 block text-sm font-medium">{tPages(($) => $.activityLogs.index.type)}</label>
              <Select value={selectedLogName} onValueChange={setSelectedLogName}>
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value={INITIAL_FILTERS.log_name}>{tPages(($) => $.activityLogs.index.allTypes)}</SelectItem>
                  {logNames.map((logName) => (
                    <SelectItem key={logName} value={logName}>
                      {logName.replace(/_/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase())}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            <div>
              <label className="mb-2 block text-sm font-medium">{tPages(($) => $.activityLogs.index.startDate)}</label>
              <Input type="date" value={startDate} onChange={(e) => setStartDate(e.target.value)} />
            </div>

            <div>
              <label className="mb-2 block text-sm font-medium">{tPages(($) => $.activityLogs.index.endDate)}</label>
              <Input type="date" value={endDate} onChange={(e) => setEndDate(e.target.value)} />
            </div>

            <div className="flex flex-col justify-end">
              <div className="flex gap-2">
                <Button onClick={applyFilters} className="flex-1">
                  {tPages(($) => $.activityLogs.index.filter)}
                </Button>
                {hasActiveFilters && (
                  <Button variant="outline" size="icon" onClick={clearFilters}>
                    <X className="h-4 w-4" />
                  </Button>
                )}
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <Datatable
        renderLeftTop={
          <Button onClick={handleExportPdf} variant="outline">
            <FileText className="mr-2 h-4 w-4" />
            {tPages(($) => $.activityLogs.index.exportPdf)}
          </Button>
        }
        data={activityLogs}
        rowId="id"
        columns={columns}
      />
    </AppLayout>
  );
}
