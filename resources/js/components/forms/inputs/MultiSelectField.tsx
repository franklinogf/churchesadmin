import MultipleSelector, { Option } from '@/components/custom-ui/MultiSelect';
import { FieldContainer } from '@/components/forms/inputs/FieldContainer';
import { FieldError } from '@/components/forms/inputs/FieldError';
import { FieldLabel } from '@/components/forms/inputs/FieldLabel';
import { convertTagsToMultiselectOptions } from '@/lib/mutliselect';
import { Tag } from '@/types/models/tag';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { useId } from 'react';

export interface MultiSelectFieldProps {
    required?: boolean;
    error?: string;
    label?: string;
    disabled?: boolean;
    className?: string;
    placeholder?: string;
    clearable?: boolean;
    value?: Option[];
    onChange?: (value: Option[]) => void;
    options: Tag[];
}

export function MultiSelectField({ error, label, disabled, className, placeholder, options, value, onChange }: MultiSelectFieldProps) {
    const { t } = useLaravelReactI18n();
    const id = useId();
    const selectOptions = convertTagsToMultiselectOptions(options);
    return (
        <FieldContainer className={className}>
            <FieldLabel disabled={disabled} error={error} id={id} label={label} />
            <MultipleSelector
                badgeClassName="[&_svg]:cursor-pointer"
                inputProps={{ id }}
                value={value ?? []}
                onChange={onChange}
                disabled={disabled}
                defaultOptions={selectOptions}
                placeholder={placeholder}
                emptyIndicator={<p className="text-center text-lg leading-10 text-gray-600 dark:text-gray-400">{t('No results')}</p>}
            />
            <FieldError error={error} />
        </FieldContainer>
    );
}
