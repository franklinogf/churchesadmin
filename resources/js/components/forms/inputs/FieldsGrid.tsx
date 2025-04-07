import { cn } from "@/lib/utils";
import { cva, type VariantProps } from "class-variance-authority";

const inputsGridVariants = cva("grid grid-cols-1 gap-2", {
  variants: {
    cols: {
      1: "lg:grid-cols-1",
      2: "lg:grid-cols-2",
      3: "lg:grid-cols-3",
      4: "lg:grid-cols-4",
      5: "lg:grid-cols-5",
      6: "lg:grid-cols-6",
    },
  },
  defaultVariants: {
    cols: 2,
  },
});

interface InputsGridProps extends VariantProps<typeof inputsGridVariants> {
  children: React.ReactNode;
  className?: string;
}
export function FieldsGrid({ children, cols, className }: InputsGridProps) {
  return <div className={cn(inputsGridVariants({ cols, className }))}>{children}</div>;
}
