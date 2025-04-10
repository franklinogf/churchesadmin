// import { TimePicker, TimePickerProps } from '@/components/custom-ui/DateTimePicker';
// import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
// import { FieldError } from '@/components/forms/inputs/FieldError';
// import { FieldLabel } from '@/components/forms/inputs/FieldLabel';
// import { formatStringToTime, formatTimeToString } from '@/lib/utils';
// import { useId } from 'react';
// interface TimeFieldProps extends Pick<TimePickerProps, 'granularity' | 'hourCycle'> {
//     error?: string;
//     label?: string;
//     disabled?: boolean;
//     className?: string;
//     value?: string;
//     onChange?: (value: string) => void;
// }
// export function TimeField({ error, label, disabled, className, granularity = 'minute', value, onChange, ...props }: TimeFieldProps) {
//     const id = useId();

//     return (
//         <FieldContainer className={className}>
//             <FieldLabel disabled={disabled} error={error} id={id} label={label} />
//             <TimePicker
//                 id={id}
//                 granularity={granularity}
//                 date={formatStringToTime(value)}
//                 onChange={(value) => {
//                     onChange?.(formatTimeToString(value));
//                 }}
//                 {...props}
//             />
//             <FieldError error={error} />
//         </FieldContainer>
//     );
// }
