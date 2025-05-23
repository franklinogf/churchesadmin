import { Form } from '@/components/forms/Form';
import { FileField } from '@/components/forms/inputs/FileField';
import { InputField } from '@/components/forms/inputs/InputField';
import { RichTextField } from '@/components/forms/inputs/RichTextField';
import { PageTitle } from '@/components/PageTitle';
import { Badge } from '@/components/ui/badge';
import type { ModelMorphName } from '@/enums';
import { useTranslations } from '@/hooks/use-translations';
import AppLayout from '@/layouts/app-layout';
import { useForm } from '@inertiajs/react';

interface Props {
  recipientsAmount: number;
  recipientsType: ModelMorphName.MEMBER | ModelMorphName.MISSIONARY;
}

type EmailForm = {
  subject: string;
  body: string;
  media: File[];
};

export default function Create({ recipientsAmount, recipientsType }: Props) {
  const { t, tChoice } = useTranslations();
  const { data, setData, errors, processing, post, progress } = useForm<EmailForm>({
    subject: '',
    body: '',
    media: [],
  });
  console.log('recipientsAmount', recipientsAmount);
  console.log('recipientsType', recipientsType);

  function handleSubmit() {
    post(route('communication.emails.store'));
  }
  return (
    <AppLayout
      title={t('New :model', { model: t('Email') })}
      breadcrumbs={[
        { title: t('Email') },
        { title: t('Members'), href: route('communication.emails.members') },
        { title: t('New :model', { model: t('Email') }) },
      ]}
    >
      <header className="mb-4">
        <PageTitle description={t('Send a new email to the members you selected')}>{t('New :model', { model: t('Email') })}</PageTitle>
        <div className="flex items-center justify-center">
          <Badge>{tChoice(':amount member selected|:amount members selected', recipientsAmount, { amount: recipientsAmount })}</Badge>
        </div>
      </header>
      <section className="mx-auto max-w-4xl">
        <Form progress={progress?.percentage} onSubmit={handleSubmit} submitLabel={t('Send email')} isSubmitting={processing}>
          <InputField required label={t('Subject')} value={data.subject} onChange={(value) => setData('subject', value)} error={errors.subject} />
          <RichTextField required label={t('Message')} value={data.body} onChange={(value) => setData('body', value)} />
          <FileField label={t('Attachments')} allowMultiple onChange={(files) => setData('media', files)} />
        </Form>
      </section>
    </AppLayout>
  );
}
