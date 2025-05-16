import { cn } from '@/lib/utils';
import type { CheckDimensions } from '@/types/models/check-layout';
import { useDroppable } from '@dnd-kit/react';

export function CheckLayoutDroppableArea({
  children,
  dimensions,
  imageUrl,
}: {
  children?: React.ReactNode;
  dimensions: CheckDimensions;
  imageUrl: string;
}) {
  const { ref, isDropTarget } = useDroppable({
    id: 'droppable-area',
  });

  return (
    <div
      id="droppable-area"
      style={{ width: dimensions.width, height: dimensions.height }}
      ref={ref}
      className={cn('relative border shadow', {
        'border-brand': isDropTarget,
      })}
    >
      <img src={imageUrl} alt="Check" className="pointer-events-none absolute inset-0 h-full w-full object-fill" />
      {children}
    </div>
  );
}
