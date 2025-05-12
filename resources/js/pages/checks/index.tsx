import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { InputField } from '@/components/forms/inputs/InputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import type { Check } from '@/types/models/check';
import { Link, useForm } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { useCallback, useState } from 'react';
import { confirmedColumns } from './includes/confirmedColumns';
import { unconfirmedColumns } from './includes/unconfirmedColumns';

type ConfirmForm = {
  checks: {
    id: number;
  }[];
  initial_check_number: string;
};
interface IndexPageProps {
  unconfirmedChecks: Check[];
  confirmedChecks: Check[];
}
export default function Index({ unconfirmedChecks, confirmedChecks }: IndexPageProps) {
  const { t } = useLaravelReactI18n();
  const [confirmedSelectedRows, setConfirmedSelectedRows] = useState<string[]>([]);

  const { data, setData, patch, errors, processing } = useForm<ConfirmForm>({
    checks: [],
    initial_check_number: '',
  });

  function confirmChecks() {
    patch(route('checks.confirm.multiple'));
  }
  const handleUnconfirmedSelection = useCallback(
    (selectedRows: Record<string, boolean>) => {
      setData(
        'checks',
        Object.keys(selectedRows).map((key) => ({ id: parseInt(key) })),
      );
    },
    [setData],
  );

  const handleConfirmedSelection = useCallback((selectedRows: Record<string, boolean>) => {
    setConfirmedSelectedRows(Object.keys(selectedRows));
  }, []);

  return (
    <AppLayout title={t('Checks')} breadcrumbs={[{ title: t('Checks'), href: route('checks.index') }]}>
      <PageTitle>{t('Checks')}</PageTitle>

      <section className="mx-auto mt-4 w-full max-w-5xl space-y-16 pb-8">
        <div>
          <header className="mt-8 flex items-center justify-between space-y-2">
            <Button size="sm">
              <Link href={route('checks.create')}>{t('New Check')}</Link>
            </Button>

            <div className="space-y-2">
              <InputField
                disabled={processing || unconfirmedChecks.length === 0}
                errorOnTop
                placeholder="Initial Check Number"
                value={data.initial_check_number}
                onChange={(value) => setData('initial_check_number', value)}
                error={errors.initial_check_number}
              />

              <div className="flex items-center justify-end gap-2">
                <SubmitButton
                  disabled={unconfirmedChecks.length === 0}
                  isSubmitting={processing}
                  variant="secondary"
                  size="sm"
                  onClick={confirmChecks}
                >
                  Confirmar cheques
                </SubmitButton>
                <Button disabled={processing || unconfirmedChecks.length === 0} variant="secondary" size="sm">
                  Confirmar cheques e imprimir
                </Button>
              </div>
              {errors.checks && <FieldError error={errors.checks} />}
            </div>
          </header>
          <PageTitle className="text-left text-xl font-semibold">{t('Unconfirmed Checks')}</PageTitle>
          <DataTable
            rowId="id"
            onSelectedRowsChange={handleUnconfirmedSelection}
            sortingState={[{ id: 'date', desc: true }]}
            visibilityState={{ expenseType: false }}
            data={unconfirmedChecks}
            columns={unconfirmedColumns}
          />
        </div>
        <div>
          <header className="mt-8 flex items-center justify-between space-y-2">
            <PageTitle className="text-left text-xl font-semibold">{t('Confirmed Checks')}</PageTitle>
            <div className="space-y-2">
              <div className="flex items-center justify-end gap-2">
                <SubmitButton disabled={confirmedSelectedRows.length === 0} variant="secondary" size="sm">
                  Imprimir cheques
                </SubmitButton>
              </div>
              {errors.checks && <FieldError error={errors.checks} />}
            </div>
          </header>
          <DataTable
            rowId="id"
            onSelectedRowsChange={handleConfirmedSelection}
            sortingState={[{ id: 'date', desc: true }]}
            visibilityState={{ expenseType: false }}
            data={confirmedChecks}
            columns={confirmedColumns}
          />
        </div>
      </section>
    </AppLayout>
  );
}
