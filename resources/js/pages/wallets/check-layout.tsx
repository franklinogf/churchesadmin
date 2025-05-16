import AppLayout from '@/layouts/app-layout';

import { type BreadcrumbItem, type SelectOption } from '@/types';
import type { CheckFieldName, CheckLayout, CheckLayoutFields } from '@/types/models/check-layout';
import { DragDropProvider, KeyboardSensor, PointerSensor } from '@dnd-kit/react';

import { InputField } from '@/components/forms/inputs/InputField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { Wallet } from '@/types/models/wallet';
import { Link, router, useForm } from '@inertiajs/react';
import { format } from 'date-fns';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { useMemo, useState } from 'react';
import { CheckLayoutDraggableField } from './components/check-layout-draggable-field';
import { CheckLayoutDroppableArea } from './components/check-layout-droppable-area';
const NEW_LAYOUT = 'new_layout';
interface CheckLayoutProps {
  checkLayouts: SelectOption[];
  checkLayout: CheckLayout | null;
  wallet: Wallet;
}

export default function CheckLayout({ checkLayouts, wallet, checkLayout }: CheckLayoutProps) {
  const { t } = useLaravelReactI18n();
  const [activeLayout, setActiveLayout] = useState(checkLayout?.id.toString() || NEW_LAYOUT);

  const createLayoutForm = useForm('create-check-layout', {
    name: '',
    image: null as File | null,
  });

  const updateLayoutForm = useForm('update-check-layout', {
    id: checkLayout?.id,
    fields: checkLayout?.fields,
  });

  function submit(e: React.FormEvent) {
    e.preventDefault();
    updateLayoutForm.put(route('wallets.check.update', [wallet.id, checkLayout?.id]), {
      preserveScroll: true,
      onSuccess: () => {},
    });
  }
  function handleChangeActiveLayout(value: string) {
    router.get(route('wallets.check.edit', [wallet.id]), { layout: value }, { replace: true });
    setActiveLayout(value);
  }

  function handleCreateLayout(e: React.FormEvent) {
    e.preventDefault();

    createLayoutForm.post(route('wallets.check.store', [wallet.id]), {
      onSuccess: (response) => {
        const checkLayout = response.props.checkLayout as unknown as CheckLayout;
        setActiveLayout(checkLayout?.id.toString());
      },
    });
  }

  const breadcrumbs: BreadcrumbItem[] = useMemo(() => [{ title: t('Wallets'), href: route('wallets.index') }, { title: t('Check layout') }], [t]);
  const isWalletLayout = wallet.checkLayout?.id.toString() === activeLayout;
  return (
    <AppLayout title={t('Check layout')} breadcrumbs={breadcrumbs}>
      <div className="space-y-6">
        <PageTitle description={t('Here you can update the printing check layout')}>{t('Check layout')}</PageTitle>

        <section className="flex w-full flex-col gap-4">
          <div className="mt-8 grid max-w-md grid-cols-2 items-center justify-between gap-4">
            <SelectField
              label={t('Select the layout you want to use for this wallet or create a new one')}
              value={activeLayout}
              onChange={handleChangeActiveLayout}
              options={[{ value: NEW_LAYOUT, label: t('Create new layout') }, ...checkLayouts]}
            />
            {activeLayout !== NEW_LAYOUT && (
              <Button disabled={isWalletLayout} variant="outline" className="self-end" asChild>
                <Link
                  href={route('wallets.check.change-layout', [wallet.id])}
                  method="put"
                  data={{ layout: activeLayout }}
                  preserveScroll
                  as="button"
                >
                  {isWalletLayout ? t('You are using this layout') : t('Use this layout')}
                </Link>
              </Button>
            )}
          </div>

          {activeLayout === NEW_LAYOUT && (
            <form onSubmit={handleCreateLayout} className="flex w-full max-w-md flex-col gap-4">
              <InputField label={t('Name')} value={createLayoutForm.data.name} onChange={(value) => createLayoutForm.setData('name', value)} />
              <Input type="file" accept="image/*" onChange={(e) => createLayoutForm.setData('image', e.target.files?.[0] || null)} />
              <SubmitButton>{t('Create layout')}</SubmitButton>
            </form>
          )}
        </section>
        {activeLayout !== NEW_LAYOUT && (
          <>
            <p className="text-sm text-neutral-600">{t('You can edit the layout by dragging the fields to the desired position')}</p>
            <form onSubmit={submit} className="space-y-6">
              {checkLayout && (
                <CheckLayoutEditor
                  checkLayout={checkLayout}
                  onChange={(fields) => {
                    // console.log('onChange', fields);
                    updateLayoutForm.setData('fields', fields);
                  }}
                />
              )}

              <div className="flex items-center gap-4">
                <SubmitButton isSubmitting={updateLayoutForm.processing}>{t('Save')}</SubmitButton>

                {/* <Transition
              show={recentlySuccessful}
              enter="transition ease-in-out"
              enterFrom="opacity-0"
              leave="transition ease-in-out"
              leaveTo="opacity-0"
            >
              <p className="text-sm text-neutral-600">{t('Saved')}</p>
            </Transition> */}
              </div>
            </form>
          </>
        )}
      </div>
    </AppLayout>
  );
}
const fieldsMap: Record<CheckFieldName, string> = {
  date: format(new Date(), 'yyyy-MM-dd'),
  amount: '549.00',
  amount_in_words: 'Five Hundred Forty-Nine and 00/100',
  payee: 'Name of Payee',
  memo: 'Memo',
};
function CheckLayoutEditor({ checkLayout, onChange }: { checkLayout: CheckLayout; onChange?: (fields: CheckLayoutFields) => void }) {
  const [fields, setFields] = useState<CheckLayoutFields>(checkLayout.fields);

  function updateFieldPosition(fieldId: keyof CheckLayoutFields, { position }: { position: { x: number; y: number } }) {
    const newFields = { ...fields };
    const field = newFields[fieldId];
    const fieldPosition = field.position || { x: 0, y: 0 };
    const newPosition = {
      x: fieldPosition.x + position.x,
      y: fieldPosition.y + position.y,
    };
    newFields[fieldId] = {
      ...field,
      position: newPosition,
    };
    setFields(newFields);
    onChange?.(newFields);
  }

  return (
    <DragDropProvider
      sensors={[KeyboardSensor, PointerSensor]}
      onDragEnd={(event) => {
        const { operation, canceled } = event;
        const { source, target } = operation;

        if (canceled) return;
        if (target?.id !== 'droppable-area') return;
        if (!source) return;

        const id = source.id as keyof CheckLayoutFields;

        updateFieldPosition(id, {
          position: {
            x: operation.transform.x || 0,
            y: operation.transform.y || 0,
          },
        });
      }}
    >
      <CheckLayoutDroppableArea dimensions={{ height: checkLayout.height, width: checkLayout.width }} imageUrl={checkLayout.imageUrl}>
        {Object.entries(fields).map(([id, { position }]) => (
          <CheckLayoutDraggableField key={id} id={id as CheckFieldName} position={position}>
            {fieldsMap[id as CheckFieldName]}
          </CheckLayoutDraggableField>
        ))}
      </CheckLayoutDroppableArea>
    </DragDropProvider>
  );
}
