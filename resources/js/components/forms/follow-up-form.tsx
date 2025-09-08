import { SelectField } from '@/components/forms/inputs/SelectField';
import { TextareaField } from '@/components/forms/inputs/TextareaField';
import { ResponsiveModal, ResponsiveModalFooterSubmit } from '@/components/responsive-modal';
import { useTranslations } from '@/hooks/use-translations';
import type { SelectOption } from '@/types';
import type { Visit, VisitFollowUp } from '@/types/models/visit';
import { useForm } from '@inertiajs/react';
import { format } from 'date-fns';
import { DatetimeField } from './inputs/DatetimeField';

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
  const { data, setData, post, put, errors, reset, processing } = useForm<FollowUpForm>({
    member_id: followUp?.memberId.toString() ?? '',
    follow_up_at: followUp?.followUpAt ?? format(new Date(), 'yyyy-MM-dd HH:mm:ss'),
    notes: followUp?.notes ?? '',
    type: followUp?.type ?? '',
  });

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    if (followUp) {
      put(route('follow-ups.update', followUp.id), {
        onSuccess: () => {
          setOpen(false);
        },
      });
    } else {
      post(route('visits.follow-ups.store', visit.id), {
        preserveState: 'errors',
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
      title={followUp ? t('Edit :model', { model: t('Follow Up') }) : t('Add :model', { model: t('Follow Up') })}
      description={followUp ? t('Edit the details of this :model', { model: t('Follow Up') }) : t('Create a new :model', { model: t('Follow Up') })}
    >
      <form className="space-y-4" onSubmit={handleSubmit}>
        <SelectField
          required
          label={t('Member')}
          value={data.member_id}
          onChange={(value) => setData('member_id', value)}
          error={errors.member_id}
          options={membersOptions}
        />
        <SelectField
          required
          label={t('Follow up type')}
          value={data.type}
          onChange={(value) => setData('type', value)}
          error={errors.type}
          options={followUpTypeOptions}
        />
        <DatetimeField
          modal
          max={new Date()}
          required
          label={t('Follow up date')}
          value={data.follow_up_at}
          onChange={(value) => setData('follow_up_at', value)}
        />

        <TextareaField label={t('Notes')} value={data.notes} onChange={(value) => setData('notes', value)} error={errors.notes} />
        <ResponsiveModalFooterSubmit isSubmitting={processing} label={t('Save')} />
      </form>
    </ResponsiveModal>
  );
}
