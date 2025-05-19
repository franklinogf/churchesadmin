import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { PageTitle } from '@/components/PageTitle';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';

import { DatatableFallback } from '@/components/fallbacks/data-table-fallback';
import AppLayout from '@/layouts/app-layout';
import type { SharedData } from '@/types';
import type { Check } from '@/types/models/check';
import { Deferred, Link, useForm } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { useCallback, useState } from 'react';
import { confirmedColumns } from './includes/confirmedColumns';
import { unconfirmedColumns } from './includes/unconfirmedColumns';

type GenerateCheckNumberForm = {
  checks: {
    id: string;
  }[];
  initial_check_number: string;
};

interface IndexPageProps extends SharedData {
  unconfirmedChecks: Check[];
  confirmedChecks: Check[];
  nextCheckNumber: number;
}

enum UnconfirmedFormAction {
  GENERATE,
  CONFIRM,
  PRINT,
}

export default function Index({ unconfirmedChecks, confirmedChecks, flash, nextCheckNumber }: IndexPageProps) {
  const { t } = useLaravelReactI18n();
  const [confirmedSelectedRows, setConfirmedSelectedRows] = useState<string[]>([]);
  const [unconfirmedAction, setUnconfirmedAction] = useState<UnconfirmedFormAction | null>(null);

  const { data, setData, errors, patch, processing } = useForm<GenerateCheckNumberForm>({
    checks: [],
    initial_check_number: nextCheckNumber.toString(),
  });

  function generateCheckNumbers(e: React.FormEvent) {
    e.preventDefault();

    setUnconfirmedAction(UnconfirmedFormAction.GENERATE);

    patch(route('checks.generate-check-number'), {
      onSuccess: () => {
        setData('initial_check_number', '');
      },
    });
  }

  function confirmChecks() {
    setUnconfirmedAction(UnconfirmedFormAction.CONFIRM);
    patch(route('checks.confirm-multiple'));
  }

  const handleUnconfirmedSelection = useCallback(
    (selectedRows: Record<string, boolean>) => {
      setData(
        'checks',
        Object.keys(selectedRows).map((key) => ({ id: key })),
      );
    },
    [setData],
  );

  const handleConfirmedSelection = useCallback((selectedRows: Record<string, boolean>) => {
    setConfirmedSelectedRows(Object.keys(selectedRows));
  }, []);

  const unconfirmedSelected = data.checks.length > 0;

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
              {errors.initial_check_number && <FieldError error={errors.initial_check_number} />}
              <form onSubmit={generateCheckNumbers}>
                <FieldsGrid>
                  <InputField
                    required
                    disabled={!unconfirmedSelected || processing}
                    errorOnTop
                    placeholder="Initial check number"
                    value={data.initial_check_number}
                    onChange={(value) => setData('initial_check_number', value)}
                  />
                  <SubmitButton
                    disabled={!unconfirmedSelected || processing}
                    isSubmitting={processing && unconfirmedAction === UnconfirmedFormAction.GENERATE}
                    size="sm"
                  >
                    {t('Generate check numbers')}
                  </SubmitButton>
                </FieldsGrid>
              </form>

              <div className="grid grid-cols-2 items-center gap-2">
                <SubmitButton
                  disabled={!unconfirmedSelected || processing}
                  isSubmitting={processing && unconfirmedAction === UnconfirmedFormAction.PRINT}
                  variant="secondary"
                  size="sm"
                >
                  {t('Confirm checks and print')}
                </SubmitButton>
                <SubmitButton
                  disabled={!unconfirmedSelected || processing}
                  isSubmitting={processing && unconfirmedAction === UnconfirmedFormAction.CONFIRM}
                  variant="secondary"
                  size="sm"
                  onClick={confirmChecks}
                >
                  {t('Confirm checks')}
                </SubmitButton>
              </div>
              {errors.checks && <FieldError error={errors.checks} />}
            </div>
          </header>
          <div className="flex flex-col items-start justify-between gap-y-2">
            <PageTitle className="text-left text-xl font-semibold">{t('Unconfirmed Checks')}</PageTitle>
            {flash.message && (
              <Alert className="w-full max-w-fit">
                <AlertDescription>{flash.message}</AlertDescription>
              </Alert>
            )}
          </div>
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
                  {t('Print checks')}
                </SubmitButton>
              </div>
            </div>
          </header>
          <Deferred data="confirmedChecks" fallback={<DatatableFallback cols={6} />}>
            <DataTable
              rowId="id"
              onSelectedRowsChange={handleConfirmedSelection}
              sortingState={[{ id: 'date', desc: true }]}
              visibilityState={{ expenseType: false }}
              data={confirmedChecks}
              columns={confirmedColumns}
            />
          </Deferred>
        </div>
      </section>
    </AppLayout>
  );
}
