import SkillController from '@/actions/App/Http/Controllers/SkillController';
import { InputField } from '@/components/forms/inputs/InputField';
import { SwitchField } from '@/components/forms/inputs/SwitchField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import type { Tag } from '@/types/models/tag';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { FieldGroup } from '../ui/field';

export function SkillForm({ skill, open, setOpen }: { skill?: Tag; open: boolean; setOpen: (open: boolean) => void }) {
  const { t: tCommon } = useTranslation('common');
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
  const MODEL = tCommon(($) => $.components.forms.skillForm.skill);

  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={
        skill
          ? tCommon(($) => $.components.forms.skillForm.editModel, { model: MODEL })
          : tCommon(($) => $.components.forms.skillForm.addModel, { model: MODEL })
      }
      description={
        skill
          ? tCommon(($) => $.components.forms.skillForm.editTheDetailsOfThisModel, { model: MODEL })
          : tCommon(($) => $.components.forms.skillForm.createANewModel, { model: MODEL })
      }
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
            <InputField label={tCommon(($) => $.components.forms.skillForm.name)} name="name" error={errors.name} />
            {userCan(skill ? TenantPermission.REGULAR_TAGS_UPDATE : TenantPermission.REGULAR_TAGS_CREATE) && (
              <SwitchField
                description={tCommon(($) => $.components.forms.skillForm.onlyAdminsWouldBeAllowedToEditAndDelete)}
                label={tCommon(($) => $.components.forms.skillForm.markThisSkillAsRegular)}
                name="is_regular"
                defaultChecked={skill?.isRegular}
                error={errors.is_regular}
              />
            )}
            <ResponsiveModalFooterSubmit isSubmitting={processing} label={tCommon(($) => $.components.forms.skillForm.save)} />
          </FieldGroup>
        )}
      </Form>
    </ResponsiveModal>
  );
}
