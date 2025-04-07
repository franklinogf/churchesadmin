import { cn } from "@/lib/utils";

export function FieldContainer({
  children,
  className,
}: {
  children: React.ReactNode;
  className?: string;
}) {
  return <div className={cn("space-y-1", className)}>{children}</div>;
}
