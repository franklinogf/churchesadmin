import { Skeleton } from '@/components/ui/skeleton';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

export function DatatableFallback({ rows = 5, cols = 3 }: { rows?: number; cols?: number }) {
  return (
    <div className="animate-pulse">
      <Table>
        <TableHeader>
          <TableRow>
            {Array.from({ length: cols }, (_, index) => (
              <TableHead key={index}>
                <Skeleton className="h-4 w-full rounded" />
              </TableHead>
            ))}
          </TableRow>
        </TableHeader>
        <TableBody>
          {Array.from({ length: rows }, (_, rowIndex) => (
            <TableRow key={rowIndex}>
              {Array.from({ length: cols }, (_, colIndex) => (
                <TableCell key={colIndex}>
                  <Skeleton className="h-4 w-full rounded" />
                </TableCell>
              ))}
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </div>
  );
}
