import VisitFollowUpController from '@/actions/App/Http/Controllers/VisitFollowUpController';
import { DateField } from '@/components/forms/inputs/DateField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { TextareaField } from '@/components/forms/inputs/TextareaField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import { useTranslations } from '@/hooks/use-translations';
import type { SelectOption } from '@/types';
import type { Visit, VisitFollowUp } from '@/types/models/visit';
import { Form } from '@inertiajs/react';
import { FieldGroup } from '../ui/field';

type FollowUpForm = {
  member_id: string;
  follow_up_at: string;
  type: string;
  notes: string;
};

interface FollowUpFormProps {
  membersOptions: SelectOption[];
  followUpTypeOptions: SelectOption[];
  visit: Visit;
  followUp?: VisitFollowUp;
  open: boolean;
  setOpen: (open: boolean) => void;
}

export function FollowUpForm({ membersOptions, followUpTypeOptions, visit, followUp, open, setOpen }: FollowUpFormProps) {
  const { t } = useTranslations();
  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={followUp ? t('Edit :model', { model: t('Follow Up') }) : t('Add :model', { model: t('Follow Up') })}
      description={followUp ? t('Edit the details of this :model', { model: t('Follow Up') }) : t('Create a new :model', { model: t('Follow Up') })}
    >
      <Form
        disableWhileProcessing
        action={followUp ? VisitFollowUpController.update(followUp.id) : VisitFollowUpController.store(visit.id)}
        onSuccess={() => {
          setOpen(false);
        }}
      >
        {({ errors, processing }) => (
          <FieldGroup>
            <SelectField
              required
              label={t('Member')}
              name="member_id"
              defaultValue={followUp?.memberId.toString()}
              error={errors.member_id}
              options={membersOptions}
            />
            <SelectField
              required
              label={t('Follow up type')}
              name="type"
              defaultValue={followUp?.type}
              error={errors.type}
              options={followUpTypeOptions}
            />
            <DateField maxDate="today" required label={t('Follow up date')} name={'follow_up_at'} />

            <TextareaField label={t('Notes')} name="notes" error={errors.notes} />
            <ResponsiveModalFooterSubmit isSubmitting={processing} label={t('Save')} />
          </FieldGroup>
        )}
      </Form>
    </ResponsiveModal>
  );
}
