import EmailController from '@/actions/App/Http/Controllers/Communication/EmailController';
import EmailListMemberController from '@/actions/App/Http/Controllers/Communication/EmailListMemberController';
import EmailListMissionaryController from '@/actions/App/Http/Controllers/Communication/EmailListMissionaryController';
import EmailListVisitorController from '@/actions/App/Http/Controllers/Communication/EmailListVisitorController';
import { Form } from '@/components/forms/Form';
import { FileField } from '@/components/forms/inputs/FileField';
import { InputField } from '@/components/forms/inputs/InputField';
import { RichTextField } from '@/components/forms/inputs/RichTextField';
import { PageTitle } from '@/components/PageTitle';
import { Badge } from '@/components/ui/badge';
import { ModelMorphName } from '@/enums/ModelMorphName';
import AppLayout from '@/layouts/app-layout';
import { useForm } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

interface Props {
  recipientsAmount: number;
  recipientsType: ModelMorphName.MEMBER | ModelMorphName.MISSIONARY | ModelMorphName.VISIT;
}

type EmailForm = {
  subject: string;
  body: string;
  media: File[];
};

export default function Create({ recipientsAmount, recipientsType }: Props) {
  const { t: tPages } = useTranslation('pages');
  const { data, setData, errors, processing, submit, progress } = useForm<EmailForm>({
    subject: '',
    body: ``,
    media: [],
  });

  function handleSubmit() {
    submit(EmailController.store());
  }
  const breadcrumbTitle =
    recipientsType === ModelMorphName.MEMBER
      ? tPages(($) => $.communication.emails.create.members)
      : recipientsType === ModelMorphName.MISSIONARY
        ? tPages(($) => $.communication.emails.create.missionaries)
        : tPages(($) => $.communication.emails.create.visitors);
  const breadcrumbHref =
    recipientsType === ModelMorphName.MEMBER
      ? EmailListMemberController()
      : recipientsType === ModelMorphName.MISSIONARY
        ? EmailListMissionaryController()
        : EmailListVisitorController();
  return (
    <AppLayout
      title={tPages(($) => $.communication.emails.create.newModel, { model: tPages(($) => $.communication.emails.create.email) })}
      breadcrumbs={[
        { title: tPages(($) => $.communication.emails.create.email), href: EmailController.index().url },
        {
          title: breadcrumbTitle,
          href: breadcrumbHref.url,
        },
        { title: tPages(($) => $.communication.emails.create.newModel, { model: tPages(($) => $.communication.emails.create.email) }) },
      ]}
    >
      <header className="mb-4">
        <PageTitle description={tPages(($) => $.communication.emails.create.sendANewEmailToTheRecipientsYouSelected)}>
          {tPages(($) => $.communication.emails.create.newModel, { model: tPages(($) => $.communication.emails.create.email) })}
        </PageTitle>
        <div className="flex items-center justify-center">
          <Badge>
            {tPages(($) => $.communication.emails.create.amountRecipientSelectedAmountRecipientsSelected, {
              count: recipientsAmount,
              amount: recipientsAmount,
            })}
          </Badge>
        </div>
      </header>
      <section className="mx-auto w-full max-w-4xl">
        <Form
          progress={progress?.percentage}
          onSubmit={handleSubmit}
          submitLabel={tPages(($) => $.communication.emails.create.sendEmail)}
          isSubmitting={processing}
        >
          <InputField
            required
            label={tPages(($) => $.communication.emails.create.subject)}
            value={data.subject}
            onChange={(value) => setData('subject', value)}
            error={errors.subject}
          />
          <RichTextField
            required
            label={tPages(($) => $.communication.emails.create.message)}
            value={data.body}
            onChange={(value) => setData('body', value)}
          />
          <FileField
            label={tPages(($) => $.communication.emails.create.attachments)}
            maxTotalFileSize="45MB"
            maxFileSize="10MB"
            allowMultiple
            onChange={(files) => setData('media', files)}
          />
        </Form>
      </section>
    </AppLayout>
  );
}
