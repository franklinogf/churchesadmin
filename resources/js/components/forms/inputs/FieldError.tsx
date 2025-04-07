interface FieldErrorProps {
  error?: string;
}
export function FieldError({ error }: FieldErrorProps) {
  if (!error) return null;

  return <div className="text-destructive text-xs">{error}</div>;
}
