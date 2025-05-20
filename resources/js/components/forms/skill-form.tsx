import { InputField } from '@/components/forms/inputs/InputField';
import { SwitchField } from '@/components/forms/inputs/SwitchField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import { UserPermission } from '@/enums/user';
import { useUser } from '@/hooks/use-user';
import type { Tag } from '@/types/models/tag';
import { useForm } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';

export function SkillForm({ skill, open, setOpen }: { skill?: Tag; open: boolean; setOpen: (open: boolean) => void }) {
  const { t } = useLaravelReactI18n();
  const { can: userCan } = useUser();
  const { data, setData, post, put, errors, reset, processing } = useForm({
    name: skill?.name ?? '',
    is_regular: skill?.isRegular ?? false,
  });

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    if (skill) {
      put(route('skills.update', skill.id), {
        onSuccess: () => {
          setOpen(false);
        },
      });
    } else {
      post(route('skills.store'), {
        preserveState: false,
        onSuccess: () => {
          setOpen(false);
          reset();
        },
      });
    }
  }

  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={skill ? t('Edit :model', { model: t('Skill') }) : t('Add :model', { model: t('Skill') })}
      description={skill ? t('Edit the details of this :model', { model: t('Skill') }) : t('Create a new :model?', { model: t('Skill') })}
    >
      <form className="space-y-4" onSubmit={handleSubmit}>
        <InputField label={t('Name')} value={data.name} onChange={(value) => setData(`name`, value)} error={errors.name} />
        {userCan(skill ? UserPermission.REGULAR_TAGS_UPDATE : UserPermission.REGULAR_TAGS_CREATE) && (
          <SwitchField
            description={t('Only admins would be allowed to edit and delete this skill')}
            label={t('Mark this skill as regular')}
            value={data.is_regular}
            onChange={(value) => setData('is_regular', value)}
            error={errors.is_regular}
          />
        )}
        <ResponsiveModalFooterSubmit isSubmitting={processing} label={t('Save')} />
      </form>
    </ResponsiveModal>
  );
}
