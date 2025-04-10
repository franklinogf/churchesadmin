import { Option } from '@/components/custom-ui/MultiSelect';
import { RelationshipTag, Tag } from '@/types/models/tag';

export function convertTagsToMultiselectOptions(tags: Tag[] | RelationshipTag[]): Option[] {
    return tags.map((tag) => ({
        label: tag.name,
        value: tag.id,
    }));
}

export function getMultiselecOptionsLabels(options: Option[]): string[] {
    return options.map((option) => option.label);
}
