import { PageTitle } from '@/components/PageTitle';
import { FormErrorList } from '@/components/forms/form-error-list';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import { usePage } from '@inertiajs/react';
import { format } from 'date-fns';
import { Printer } from 'lucide-react';
import { useState } from 'react';

export default function EntriesExpensesReport() {
  const { t } = useTranslations();
  const [startDate, setStartDate] = useState(new Date());
  const [endDate, setEndDate] = useState(new Date());

  // Generate years array for select (10 years back until current year)
  const currentYear = new Date().getFullYear();
  const years = Array.from({ length: 11 }, (_, i) => currentYear - 10 + i);

  // Months for select
  const months = Array.from({ length: 12 }, (_, i) => i);

  // No need for an effect hook as we're generating the URL when the button is clicked

  const handlePrintPdf = () => {
    const formattedStartDate = format(startDate, 'yyyy-MM-dd');
    const formattedEndDate = format(endDate, 'yyyy-MM-dd');
    const url = route('reports.entries_expenses.pdf', {
      startDate: formattedStartDate,
      endDate: formattedEndDate,
    });
    window.open(url, '_blank');
  };

  const handleMonthChange = (date: Date, setDate: React.Dispatch<React.SetStateAction<Date>>) => (month: string) => {
    setDate((prevDate) => {
      const newDate = new Date(prevDate);
      newDate.setMonth(parseInt(month, 10));
      return newDate;
    });
  };

  const handleYearChange = (date: Date, setDate: React.Dispatch<React.SetStateAction<Date>>) => (year: string) => {
    setDate((prevDate) => {
      const newDate = new Date(prevDate);
      newDate.setFullYear(parseInt(year, 10));
      return newDate;
    });
  };

  return (
    <AppLayout
      title={t('Entries and Expenses Report')}
      breadcrumbs={[{ title: t('Reports'), href: route('reports') }, { title: t('Entries and Expenses Report') }]}
    >
      <PageTitle>{t('Entries and Expenses Report')}</PageTitle>
      <FormErrorList errors={usePage().props.errors} />

      <Card className="mx-auto mb-8 w-full max-w-2xl">
        <CardContent className="p-6">
          <h2 className="mb-4 text-xl font-semibold">{t('Select Date Range for Report')}</h2>

          <div className="mb-6 space-y-4">
            <div>
              <label className="mb-2 block text-sm font-medium">{t('Start Date')}</label>
              <FieldsGrid>
                <SelectField
                  label={t('Month')}
                  value={startDate.getMonth().toString()}
                  onChange={handleMonthChange(startDate, setStartDate)}
                  options={months.map((month) => ({
                    value: month.toString(),
                    label: new Date(0, month).toLocaleString('default', { month: 'long' }),
                  }))}
                />

                <SelectField
                  label={t('Year')}
                  value={startDate.getFullYear().toString()}
                  onChange={handleYearChange(startDate, setStartDate)}
                  options={years.map((year) => ({ value: year.toString(), label: year.toString() }))}
                />
              </FieldsGrid>
            </div>

            <div>
              <label className="mb-2 block text-sm font-medium">{t('End Date')}</label>
              <FieldsGrid>
                <SelectField
                  label={t('Month')}
                  value={endDate.getMonth().toString()}
                  onChange={handleMonthChange(endDate, setEndDate)}
                  options={months.map((month) => ({
                    value: month.toString(),
                    label: new Date(0, month).toLocaleString('default', { month: 'long' }),
                  }))}
                />

                <SelectField
                  label={t('Year')}
                  value={endDate.getFullYear().toString()}
                  onChange={handleYearChange(endDate, setEndDate)}
                  options={years.map((year) => ({ value: year.toString(), label: year.toString() }))}
                />
              </FieldsGrid>
            </div>
          </div>
          <div>
            <Button className="w-full" onClick={handlePrintPdf}>
              <Printer className="mr-2 h-5 w-5" />
              {t('Generate PDF for Selected Date Range')}
            </Button>
          </div>
        </CardContent>
      </Card>

      <div className="text-center text-gray-500">{t('The PDF will include all entries and expenses for the selected date range.')}</div>
    </AppLayout>
  );
}
