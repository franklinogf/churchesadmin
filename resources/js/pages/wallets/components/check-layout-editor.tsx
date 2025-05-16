import type { CheckFieldName, CheckLayout, CheckLayoutFields } from '@/types/models/check-layout';
import { DragDropProvider, KeyboardSensor, PointerSensor } from '@dnd-kit/react';

import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Slider } from '@/components/ui/slider';
import { Transition } from '@headlessui/react';
import { useForm } from '@inertiajs/react';
import { format } from 'date-fns';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { useState } from 'react';
import { CheckLayoutDraggableField } from './check-layout-draggable-field';
import { CheckLayoutDroppableArea } from './check-layout-droppable-area';

const initialFieldsMap: Record<CheckFieldName, string> = {
  date: format(new Date(), 'yyyy-MM-dd'),
  amount: '549.00',
  amount_in_words: 'Five Hundred Forty-Nine and 00/100',
  payee: 'Name of Payee',
  memo: 'Memo',
};

export function CheckLayoutEditor({ checkLayout }: { checkLayout: CheckLayout }) {
  const { t } = useLaravelReactI18n();
  const [fieldsMap, setFieldsMap] = useState<Record<CheckFieldName, string>>(initialFieldsMap);
  const { data, setData, put, processing, recentlySuccessful } = useForm('update-check-layout', {
    id: checkLayout.id,
    fields: checkLayout.fields,
    width: checkLayout.width,
    height: checkLayout.height,
  });

  function updateFieldPosition(fieldId: CheckFieldName, { position }: { position: { x: number; y: number } }) {
    const newFields = { ...data.fields };
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
    setData('fields', newFields);
  }

  function handleFieldChange(fieldId: CheckFieldName, value: string) {
    setFieldsMap((prev) => ({
      ...prev,
      [fieldId]: value,
    }));
  }

  function submit(e: React.FormEvent) {
    e.preventDefault();
    put(route('check-layout.update', [checkLayout.id]), {
      preserveScroll: true,
      onSuccess: () => {},
    });
  }

  return (
    <div className="grid grid-cols-1 gap-4 lg:grid-cols-3">
      <form onSubmit={submit} className="col-span-2 space-y-6">
        <p className="text-muted-foreground mb-4 text-sm">{t('You can edit the layout by dragging the fields to the desired position')}</p>
        <FieldsGrid>
          <div className="w-full">
            <p>
              {t('Width')} ({data.width}px)
            </p>
            <Slider value={[data.width]} max={1000} onValueChange={(value) => setData('width', value[0]!)} />
          </div>
          <div className="w-full">
            <p>
              {t('Height')} ({data.height}px)
            </p>
            <Slider value={[data.height]} max={1000} onValueChange={(value) => setData('height', value[0]!)} />
          </div>
        </FieldsGrid>
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
          <CheckLayoutDroppableArea dimensions={{ height: data.height, width: data.width }} imageUrl={checkLayout.imageUrl}>
            {Object.entries(data.fields).map(([id, { position }]) => (
              <CheckLayoutDraggableField key={id} id={id as CheckFieldName} position={position}>
                {fieldsMap[id as CheckFieldName]}
              </CheckLayoutDraggableField>
            ))}
          </CheckLayoutDroppableArea>
        </DragDropProvider>
        <div className="flex items-center gap-4">
          <SubmitButton isSubmitting={processing}>{t('Save')}</SubmitButton>

          <Transition
            show={recentlySuccessful}
            enter="transition ease-in-out"
            enterFrom="opacity-0"
            leave="transition ease-in-out"
            leaveTo="opacity-0"
          >
            <p className="text-sm text-neutral-600">{t('Saved')}</p>
          </Transition>
        </div>
      </form>
      <section>
        <Card className="sticky top-5 mt-4">
          <CardHeader>
            <CardTitle>{t('Preview')}</CardTitle>
            <CardDescription>{t('This is how the information will look on the check layout')}</CardDescription>
          </CardHeader>
          <CardContent>
            {Object.entries(fieldsMap).map(([id, value]) => (
              <div key={id} className="mb-4 gap-2">
                <InputField
                  label={id.toUpperCase().replaceAll('_', ' ')}
                  type="text"
                  value={value}
                  onChange={(value) => handleFieldChange(id as CheckFieldName, value)}
                  placeholder={t('Enter the value for this field')}
                />
              </div>
            ))}
          </CardContent>
        </Card>
      </section>
    </div>
  );
}
