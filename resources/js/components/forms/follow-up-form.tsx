import VisitFollowUpController from '@/actions/App/Http/Controllers/VisitFollowUpController';
import { DateField } from '@/components/forms/inputs/DateField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { TextareaField } from '@/components/forms/inputs/TextareaField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import type { SelectOption } from '@/types';
import type { Visit, VisitFollowUp } from '@/types/models/visit';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
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
  const { t: tCommon } = useTranslation('common');
  return (
    <ResponsiveModal
      open={open}
      setOpen={setOpen}
      title={
        followUp
          ? tCommon(($) => $.components.forms.followUpForm.editModel, { model: tCommon(($) => $.components.forms.followUpForm.followUp) })
          : tCommon(($) => $.components.forms.followUpForm.addModel, { model: tCommon(($) => $.components.forms.followUpForm.followUp) })
      }
      description={
        followUp
          ? tCommon(($) => $.components.forms.followUpForm.editTheDetailsOfThisModel, {
              model: tCommon(($) => $.components.forms.followUpForm.followUp),
            })
          : tCommon(($) => $.components.forms.followUpForm.createANewModel, { model: tCommon(($) => $.components.forms.followUpForm.followUp) })
      }
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
              label={tCommon(($) => $.components.forms.followUpForm.member)}
              name="member_id"
              defaultValue={followUp?.memberId.toString()}
              error={errors.member_id}
              options={membersOptions}
            />
            <SelectField
              required
              label={tCommon(($) => $.components.forms.followUpForm.followUpType)}
              name="type"
              defaultValue={followUp?.type}
              error={errors.type}
              options={followUpTypeOptions}
            />
            <DateField maxDate="today" required label={tCommon(($) => $.components.forms.followUpForm.followUpDate)} name={'follow_up_at'} />

            <TextareaField label={tCommon(($) => $.components.forms.followUpForm.notes)} name="notes" error={errors.notes} />
            <ResponsiveModalFooterSubmit isSubmitting={processing} label={tCommon(($) => $.components.forms.followUpForm.save)} />
          </FieldGroup>
        )}
      </Form>
    </ResponsiveModal>
  );
}
