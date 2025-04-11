import { Option } from '@/components/custom-ui/MultiSelect';
import { SelectOption } from '@/types';
import { Tag, TagRelationship } from '@/types/models/tag';
import { Role } from '@/types/models/user';

export function convertTagsToMultiselectOptions(tags: Tag[] | TagRelationship[] | Option[] | SelectOption[]): Option[] {
  if (!tags.length) {
    return [];
  }
  return tags.map((tag) => {
    if ('value' in tag) {
      if ('disabled' in tag) {
        return tag;
      }
      return {
        label: tag.label,
        value: tag.value.toString(),
      };
    }
    return {
      label: tag.name,
      value: tag.id.toString(),
    };
  });
}

export function convertRolesToMultiselectOptions(roles: Role[]): Option[] {
  if (!roles.length) {
    return [];
  }
  return roles.map((role) => ({
    label: role.label || role.name,
    value: role.name,
  }));
}

export function getMultiselecOptionsLabels(options: Option[]): string[] {
  return options.map((option) => option.label);
}
export function getMultiselecOptionsValues(options: Option[]): string[] {
  return options.map((option) => option.value);
}
