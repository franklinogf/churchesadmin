import type { CheckFieldName, CheckLayout, CheckLayoutField, CheckLayoutPosition } from '@/types/models/check-layout';
import { DragDropProvider, KeyboardSensor, PointerSensor } from '@dnd-kit/react';

import CheckLayoutController from '@/actions/App/Http/Controllers/CheckLayoutController';
import { FormErrorList } from '@/components/forms/form-error-list';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { SubmitButton } from '@/components/forms/SubmitButton';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Slider } from '@/components/ui/slider';
import { useTranslations } from '@/hooks/use-translations';
import { Transition } from '@headlessui/react';
import { useForm } from '@inertiajs/react';
import { format } from 'date-fns';
import { PlusIcon } from 'lucide-react';
import { useState } from 'react';
import { CheckLayoutDraggableField } from './check-layout-draggable-field';
import { CheckLayoutDroppableArea } from './check-layout-droppable-area';
type CheckLayoutForm = {
  fields: CheckLayoutField[];
  width: number;
  height: number;
};
export function CheckLayoutEditor({ checkLayout }: { checkLayout: CheckLayout }) {
  const { t } = useTranslations();
  const initialFieldsMap: Record<CheckFieldName, string> = {
    date: format(new Date(), 'yyyy-MM-dd'),
    amount: '549.00',
    amount_in_words: t('Five Hundred Forty-Nine and 00/100'),
    payee: t('Name of Payee'),
    memo: t('Memo'),
  };
  const [fieldsMap, setFieldsMap] = useState<Record<CheckFieldName, string>>(initialFieldsMap);
  const { data, setData, submit, processing, recentlySuccessful, errors } = useForm<CheckLayoutForm>({
    fields: checkLayout.fields || [],
    width: checkLayout.width,
    height: checkLayout.height,
  });

  function updateFieldPosition(index: number, { position }: CheckLayoutPosition) {
    const updatedFields = [...data.fields];
    if (!updatedFields[index]) {
      return;
    }
    const newPosition = {
      x: updatedFields[index].position.x + position.x,
      y: updatedFields[index].position.y + position.y,
    };
    updatedFields[index] = {
      ...updatedFields[index],
      position: newPosition,
    };

    setData('fields', updatedFields);
  }

  function handleAddField(fieldId: CheckFieldName) {
    setData('fields', [...data.fields, { target: fieldId, position: { x: 10, y: 15 } }]);
  }

  function handleRemoveField(index: number) {
    const updatedFields = [...data.fields];
    updatedFields.splice(index, 1);
    setData('fields', updatedFields);
  }

  function handleFieldChange(fieldId: CheckFieldName, value: string) {
    setFieldsMap((prev) => ({
      ...prev,
      [fieldId]: value,
    }));
  }

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    submit(CheckLayoutController.update(checkLayout.id), {
      preserveScroll: true,
      onSuccess: () => {},
    });
  }

  return (
    <div className="grid grid-cols-1 gap-4 lg:grid-cols-3">
      <form onSubmit={handleSubmit} className="col-span-2 space-y-6">
        <p className="text-muted-foreground mb-4 text-sm">{t('You can edit the layout by dragging the fields to the desired position')}</p>
        <FormErrorList errors={errors} />
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

            const id = source.id as number;

            updateFieldPosition(id, {
              position: {
                x: operation.transform.x || 0,
                y: operation.transform.y || 0,
              },
            });
          }}
        >
          <CheckLayoutDroppableArea dimensions={{ height: data.height, width: data.width }} imageUrl={checkLayout.imageUrl}>
            {data.fields &&
              data.fields.map((field, index) => (
                <CheckLayoutDraggableField
                  key={`${field.target}-${index}`}
                  id={index}
                  position={field.position}
                  onRemoveField={() => handleRemoveField(index)}
                >
                  {fieldsMap[field.target]}
                </CheckLayoutDraggableField>
              ))}
          </CheckLayoutDroppableArea>
        </DragDropProvider>
        <div className="flex items-center justify-center gap-4">
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
              <div key={id} className="mb-4 flex items-center gap-2">
                <InputField
                  className="grow"
                  label={id.toUpperCase().replaceAll('_', ' ')}
                  type="text"
                  value={value}
                  onChange={(value) => handleFieldChange(id as CheckFieldName, value === '' ? initialFieldsMap[id as CheckFieldName] : value)}
                  placeholder={t('Enter the value for this field')}
                />
                <Button variant="outline" size="icon" className="self-end" onClick={() => handleAddField(id as CheckFieldName)}>
                  <PlusIcon className="h-4 w-4" />
                </Button>
              </div>
            ))}
          </CardContent>
        </Card>
      </section>
    </div>
  );
}
