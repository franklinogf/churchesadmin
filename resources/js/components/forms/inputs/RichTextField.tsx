// import { RichTextEditor } from '@/components/custom-ui/RichTextEditor';
// import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
// import { FieldError } from '@/components/forms/inputs/FieldError';
// import { cn } from '@/lib/utils';

// interface RichTextFieldProps {
//     value: string;
//     onChange: (value: string) => void;
//     className?: string;
//     disabled?: boolean;
//     error?: string;
//     label?: string;
// }
// export function RichTextField({ value, onChange, className, disabled, error, label }: RichTextFieldProps) {
//     return (
//         <FieldContainer className={className}>
//             <p
//                 className={cn(
//                     'flex items-center gap-2 text-sm leading-none font-medium select-none group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50',
//                     {
//                         'text-destructive': error,
//                         'text-muted-foreground/80': disabled,
//                     },
//                 )}
//             >
//                 {label}
//             </p>
//             <RichTextEditor disabled={disabled} value={value} onChange={onChange} />
//             <FieldError error={error} />
//         </FieldContainer>
//     );
// }
