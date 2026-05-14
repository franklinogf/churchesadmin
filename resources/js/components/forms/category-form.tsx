import CategoryController from '@/actions/App/Http/Controllers/CategoryController';
import { InputField } from '@/components/forms/inputs/InputField';
import { SwitchField } from '@/components/forms/inputs/SwitchField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import { FieldGroup, FieldSet } from '@/components/ui/field';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import type { Tag } from '@/types/models/tag';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

export function CategoryForm({ category, open, setOpen }: { category?: Tag; open: boolean; setOpen: (open: boolean) => void }) {
  const { t: tCommon } = useTranslation('common');
  const { can: userCan } = useUser();

  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={
        category
          ? tCommon(($) => $.components.forms.categoryForm.editModel, { model: tCommon(($) => $.components.forms.categoryForm.category) })
          : tCommon(($) => $.components.forms.categoryForm.addModel, { model: tCommon(($) => $.components.forms.categoryForm.category) })
      }
      description={
        category
          ? tCommon(($) => $.components.forms.categoryForm.editTheDetailsOfThisModel, {
              model: tCommon(($) => $.components.forms.categoryForm.category),
            })
          : tCommon(($) => $.components.forms.categoryForm.createANewModel, { model: tCommon(($) => $.components.forms.categoryForm.category) })
      }
    >
      <Form
        disableWhileProcessing
        transform={(data) => ({ ...data, is_regular: data.is_regular === 'on' ? true : false })}
        onSuccess={() => {
          setOpen(false);
        }}
        action={category ? CategoryController.update({ id: category.id }) : CategoryController.store()}
      >
        {({ errors, processing }) => (
          <FieldSet>
            <FieldGroup>
              <InputField
                defaultValue={category?.name}
                label={tCommon(($) => $.components.forms.categoryForm.name)}
                name="name"
                error={errors.name}
              />
              {userCan(category ? TenantPermission.REGULAR_TAGS_UPDATE : TenantPermission.REGULAR_TAGS_CREATE) && (
                <SwitchField
                  description={tCommon(($) => $.components.forms.categoryForm.onlyAdminsWouldBeAllowedToEditAndDelete)}
                  label={tCommon(($) => $.components.forms.categoryForm.markThisCategoryAsRegular)}
                  name="is_regular"
                  defaultChecked={category?.isRegular}
                  error={errors.is_regular}
                />
              )}
              <ResponsiveModalFooterSubmit isSubmitting={processing} label={tCommon(($) => $.components.forms.categoryForm.save)} />
            </FieldGroup>
          </FieldSet>
        )}
      </Form>
    </ResponsiveModal>
  );
}
