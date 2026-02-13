import SkillController from '@/actions/App/Http/Controllers/SkillController';
import { InputField } from '@/components/forms/inputs/InputField';
import { SwitchField } from '@/components/forms/inputs/SwitchField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import { TenantPermission } from '@/enums/TenantPermission';
import { useTranslations } from '@/hooks/use-translations';
import { useUser } from '@/hooks/use-user';
import type { Tag } from '@/types/models/tag';
import { Form } from '@inertiajs/react';
import { FieldGroup } from '../ui/field';

export function SkillForm({ skill, open, setOpen }: { skill?: Tag; open: boolean; setOpen: (open: boolean) => void }) {
  const { t } = useTranslations();
  const { can: userCan } = useUser();
  //   const { data, setData, post, put, errors, reset, processing } = useForm({
  //     name: skill?.name ?? '',
  //     is_regular: skill?.isRegular ?? false,
  //   });

  //   function handleSubmit(e: React.FormEvent) {
  //     e.preventDefault();
  //     if (skill) {
  //       put(route('skills.update', skill.id), {
  //         onSuccess: () => {
  //           setOpen(false);
  //         },
  //       });
  //     } else {
  //       post(route('skills.store'), {
  //         preserveState: false,
  //         onSuccess: () => {
  //           setOpen(false);
  //           reset();
  //         },
  //       });
  //     }
  //   }
  const MODEL = t('Skill');

  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={skill ? t('Edit :model', { model: MODEL }) : t('Add :model', { model: MODEL })}
      description={skill ? t('Edit the details of this :model', { model: MODEL }) : t('Create a new :model', { model: MODEL })}
    >
      <Form
        disableWhileProcessing
        transform={(data) => ({ ...data, is_regular: data.is_regular === 'on' ? true : false })}
        onSuccess={() => {
          setOpen(false);
        }}
        action={skill ? SkillController.update({ id: skill.id }) : SkillController.store()}
      >
        {({ errors, processing }) => (
          <FieldGroup>
            <InputField label={t('Name')} name="name" error={errors.name} />
            {userCan(skill ? TenantPermission.REGULAR_TAGS_UPDATE : TenantPermission.REGULAR_TAGS_CREATE) && (
              <SwitchField
                description={t('Only admins would be allowed to edit and delete this skill')}
                label={t('Mark this skill as regular')}
                name="is_regular"
                defaultChecked={skill?.isRegular}
                error={errors.is_regular}
              />
            )}
            <ResponsiveModalFooterSubmit isSubmitting={processing} label={t('Save')} />
          </FieldGroup>
        )}
      </Form>
    </ResponsiveModal>
  );
}
