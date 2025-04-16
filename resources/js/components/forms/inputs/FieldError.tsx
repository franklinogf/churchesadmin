interface FieldErrorProps {
  error?: string;
}
export function FieldError({ error }: FieldErrorProps) {
  if (!error) return null;

  return <p className="text-sm text-red-600 dark:text-red-400">{error}</p>;
}
