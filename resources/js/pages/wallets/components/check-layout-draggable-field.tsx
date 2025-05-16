import { cn } from '@/lib/utils';
import type { CheckFieldName } from '@/types/models/check-layout';
import { RestrictToElement } from '@dnd-kit/dom/modifiers';
import { useDraggable } from '@dnd-kit/react';

export function CheckLayoutDraggableField({
  id,
  position,
  children,
}: {
  id: CheckFieldName;
  position: { x: number; y: number };
  children?: React.ReactNode;
}) {
  const { ref, isDragging } = useDraggable({
    id,
    modifiers: [
      RestrictToElement.configure({
        element: document.querySelector('#droppable-area'),
      }),
    ],
  });

  return (
    <button
      style={{ top: position.y, left: position.x }}
      className={cn('absolute cursor-move text-sm text-nowrap text-black hover:underline', { 'bg-brand/20': !isDragging })}
      type="button"
      ref={ref}
    >
      {children}
    </button>
  );
}
