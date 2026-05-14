import { Skeleton } from '../ui/skeleton';

interface DatatableSkeletonProps {
  numberOfRows?: number;
  numberOfColumns?: number;
}
export function DatatableSkeleton({ numberOfRows = 5, numberOfColumns = 3 }: DatatableSkeletonProps) {
  return (
    <div className="space-y-3">
      <div className="rounded-lg border">
        <div className="bg-muted/50 flex items-center justify-around border-b px-4 py-3">
          {[...Array(numberOfColumns)].map((_, i) => (
            <Skeleton key={i} className="h-4 w-24" />
          ))}
        </div>
        {[...Array(numberOfRows)].map((_, i) => (
          <div key={i} className="border-muted/20 flex items-center justify-around border-b px-4 py-3 last:border-b-0">
            {[...Array(numberOfColumns)].map((_, j) => (
              <Skeleton key={j} className="h-4 w-24" />
            ))}
          </div>
        ))}
      </div>
    </div>
  );
}
