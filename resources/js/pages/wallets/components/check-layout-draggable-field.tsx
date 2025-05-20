import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { RestrictToElement } from '@dnd-kit/dom/modifiers';
import { useDraggable } from '@dnd-kit/react';
import { XSquareIcon } from 'lucide-react';

export function CheckLayoutDraggableField({
  id,
  position,
  children,
  onRemoveField,
}: {
  id: number;
  position: { x: number; y: number };
  children?: React.ReactNode;
  onRemoveField?: (id: number) => void;
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
    <div ref={ref} style={{ top: position.y, left: position.x }} className="absolute flex items-center justify-center">
      <button className={cn('cursor-move text-sm text-nowrap text-black hover:underline', { 'bg-primary/10': !isDragging })} type="button">
        {children}
      </button>
      <Button type="button" variant="link" className="text-destructive size-5" onClick={() => onRemoveField?.(id)}>
        <XSquareIcon className="size-4" />
        <span className="sr-only">Remove</span>
      </Button>
    </div>
  );
}
