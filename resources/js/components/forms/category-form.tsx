import CategoryController from '@/actions/App/Http/Controllers/CategoryController';
import { InputField } from '@/components/forms/inputs/InputField';
import { SwitchField } from '@/components/forms/inputs/SwitchField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import { FieldGroup, FieldSet } from '@/components/ui/field';
import { TenantPermission } from '@/enums/TenantPermission';
import { useTranslations } from '@/hooks/use-translations';
import { useUser } from '@/hooks/use-user';
import type { Tag } from '@/types/models/tag';
import { Form } from '@inertiajs/react';

export function CategoryForm({ category, open, setOpen }: { category?: Tag; open: boolean; setOpen: (open: boolean) => void }) {
  const { t } = useTranslations();
  const { can: userCan } = useUser();

  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={category ? t('Edit :model', { model: t('Category') }) : t('Add :model', { model: t('Category') })}
      description={category ? t('Edit the details of this :model', { model: t('Category') }) : t('Create a new :model', { model: t('Category') })}
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
              <InputField defaultValue={category?.name} label={t('Name')} name="name" error={errors.name} />
              {userCan(category ? TenantPermission.REGULAR_TAGS_UPDATE : TenantPermission.REGULAR_TAGS_CREATE) && (
                <SwitchField
                  description={t('Only admins would be allowed to edit and delete this category')}
                  label={t('Mark this category as regular')}
                  name="is_regular"
                  defaultChecked={category?.isRegular}
                  error={errors.is_regular}
                />
              )}
              <ResponsiveModalFooterSubmit isSubmitting={processing} label={t('Save')} />
            </FieldGroup>
          </FieldSet>
        )}
      </Form>
    </ResponsiveModal>
  );
}
