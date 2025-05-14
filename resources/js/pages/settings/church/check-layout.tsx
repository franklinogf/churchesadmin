import HeadingSmall from '@/components/heading-small';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/church-layout';
import { cn } from '@/lib/utils';
import { type BreadcrumbItem, type CheckFieldName } from '@/types';
import { DndContext, PointerSensor, pointerWithin, useDraggable, useDroppable, useSensor, useSensors, type DragEndEvent } from '@dnd-kit/core';
import { restrictToWindowEdges } from '@dnd-kit/modifiers';
import { Transition } from '@headlessui/react';
import { useForm } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { useMemo, useState, type FormEventHandler } from 'react';

type CheckField = { id: CheckFieldName; label: string; position: { x: number; y: number } };
type CheckLayout = CheckField[];

export default function CheckLayout({ layout }: { layout: CheckLayout }) {
  const { t } = useLaravelReactI18n();

  const { data, setData, patch, errors, processing, recentlySuccessful } = useForm();

  const submit: FormEventHandler = (e) => {
    e.preventDefault();

    patch(route('church.check.update'), {
      preserveScroll: true,
      onSuccess: () => {},
    });
  };

  const breadcrumbs: BreadcrumbItem[] = useMemo(() => [{ title: t('Check layout'), href: route('church.check.edit') }], [t]);

  return (
    <AppLayout title={t('Check layout')} breadcrumbs={breadcrumbs}>
      <SettingsLayout>
        <div className="space-y-6">
          <HeadingSmall title={t('Check layout')} description={t('Update the printing check layout')} />

          <form onSubmit={submit} className="space-y-6">
            <CheckLayoutEditor fields={layout} />
            <div className="flex items-center gap-4">
              <Button disabled={processing}>{t('Save')}</Button>

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
        </div>
      </SettingsLayout>
    </AppLayout>
  );
}

function CheckLayoutEditor({ fields: initialFields, onChange }: { fields: CheckLayout; onChange?: (fields: CheckLayout) => void }) {
  const [fields, setFields] = useState<CheckLayout>(initialFields);
  const sensors = useSensors(useSensor(PointerSensor));

  function handleDragEnd(event: DragEndEvent) {
    const { over, active } = event;

    const id = active.id as CheckFieldName;

    if (!over) {
      setFields((prev) => ({ ...prev, [id]: false }));
    }
    if (over && over.id === 'droppable-area') {
      const updatedFields = fields.map((field) =>
        field.id === id
          ? {
              ...field,
              position: {
                x: (event.delta?.x || 0) + (field.position?.x || 0),
                y: (event.delta?.y || 0) + (field.position?.y || 0),
              },
            }
          : field,
      );
      setFields(updatedFields);
      onChange?.(updatedFields);
    }
  }

  return (
    <DndContext modifiers={[restrictToWindowEdges]} collisionDetection={pointerWithin} sensors={sensors} onDragEnd={handleDragEnd}>
      <CheckLayoutDroppableArea>
        {fields.map(({ id, label, position }) => (
          <CheckLayoutDraggableField key={id} id={id} position={position}>
            {label}
          </CheckLayoutDraggableField>
        ))}
      </CheckLayoutDroppableArea>
    </DndContext>
  );
}

function CheckLayoutDroppableArea({ children }: { children?: React.ReactNode }) {
  const { isOver, setNodeRef, rect } = useDroppable({
    id: 'droppable-area',
  });
  console.log('rect', rect);

  return (
    <div
      ref={setNodeRef}
      className={cn('relative aspect-[6/2.5] border shadow', {
        'border-brand': isOver,
      })}
    >
      <img src="https://test.churchesadmin.test/tenancy/assets/check.png" alt="Check" className="absolute inset-0 h-full w-full object-fill" />
      {children}
    </div>
  );
}

function CheckLayoutDraggableField({
  id,
  position,
  children,
}: {
  id: CheckFieldName;
  position: { x: number; y: number };
  children: React.ReactNode;
}) {
  const { attributes, listeners, setNodeRef, transform } = useDraggable({
    id,
  });

  const style = {
    transform: `translate3d(${(transform?.x ?? 0) + position.x}px, ${(transform?.y ?? 0) + position.y}px, 0)`,
  };
  return (
    <button
      className={cn('text-accent absolute cursor-move text-xs hover:underline', {
        'transition-transform duration-200': transform === null,
      })}
      {...attributes}
      {...listeners}
      type="button"
      ref={setNodeRef}
      style={style}
    >
      {children}
    </button>
  );
}
