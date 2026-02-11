import { FieldLabel as FieldLabelUi } from '@/components/ui/field';
import { cn } from '@/lib/utils';
import { RequiredFieldIcon } from '../RequiredFieldIcon';

export function FieldLabel({
  required,
  label,
  disabled,
  id,
  className,
}: {
  required?: boolean;
  label?: string;
  disabled?: boolean;
  id?: string;
  className?: string;
}) {
  if (!label) return null;

  return (
    <FieldLabelUi
      asChild={id === undefined}
      aria-disabled={disabled}
      className={cn(
        'flex max-w-fit gap-x-0.5',
        {
          'text-muted-foreground/80': disabled,
        },
        className,
      )}
      htmlFor={id}
    >
      {id === undefined ? (
        <div>
          <span>{label}</span>
          {required && <RequiredFieldIcon className="self-start" />}
        </div>
      ) : (
        <>
          <span>{label}</span>
          {required && <RequiredFieldIcon className="self-start" />}
        </>
      )}
    </FieldLabelUi>
  );
}
