import { InputField } from '@/components/forms/inputs/InputField';
import { SwitchField } from '@/components/forms/inputs/SwitchField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import { UserPermission } from '@/enums/user';
import { useUser } from '@/hooks/use-user';
import type { Tag } from '@/types/models/tag';
import { useForm } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';

export function CategoryForm({ category, open, setOpen }: { category?: Tag; open: boolean; setOpen: (open: boolean) => void }) {
  const { t } = useLaravelReactI18n();
  const { can: userCan } = useUser();
  const { data, setData, post, put, errors, reset, processing } = useForm({
    name: category?.name ?? '',
    is_regular: category?.isRegular ?? false,
  });

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    if (category) {
      put(route('categories.update', category.id), {
        onSuccess: () => {
          setOpen(false);
        },
      });
    } else {
      post(route('categories.store'), {
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
      title={category ? t('Edit :model', { model: t('Category') }) : t('Add :model', { model: t('Category') })}
      description={category ? t('Edit the details of this :model', { model: t('Category') }) : t('Create a new :model', { model: t('Category') })}
    >
      <form className="space-y-4" onSubmit={handleSubmit}>
        <InputField label={t('Name')} value={data.name} onChange={(value) => setData(`name`, value)} error={errors.name} />
        {userCan(category ? UserPermission.REGULAR_TAG_UPDATE : UserPermission.REGULAR_TAG_CREATE) && (
          <SwitchField
            description={t('Only admins would be allowed to edit and delete this category')}
            label={t('Mark this category as regular')}
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
