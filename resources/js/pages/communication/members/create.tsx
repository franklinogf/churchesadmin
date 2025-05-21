import { Form } from '@/components/forms/Form';
import { InputField } from '@/components/forms/inputs/InputField';
import { RichTextField } from '@/components/forms/inputs/RichTextField';
import { PageTitle } from '@/components/PageTitle';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/app-layout';
import { useForm } from '@inertiajs/react';

interface Props {
  membersAmount: number;
  membersId: string[];
}

type EmailForm = {
  subject: string;
  body: string;
  membersId: string[];
};

export default function Create({ membersAmount, membersId }: Props) {
  const { data, setData, errors, processing, post } = useForm<EmailForm>({
    subject: '',
    body: '',
    membersId,
  });
  function handleSubmit() {
    // Handle form submission
    post(route('messages.members.store'));
  }
  console.log(errors);
  return (
    <AppLayout
      title="New email"
      breadcrumbs={[{ title: 'Email' }, { title: 'Members', href: route('messages.members.index') }, { title: 'New email' }]}
    >
      <header className="mb-4">
        <PageTitle description="Send a new email to the members you selected">New Email</PageTitle>
        <div className="flex items-center justify-center">
          <Badge>{membersAmount} members selected</Badge>
        </div>
      </header>
      <section className="mx-auto max-w-4xl">
        <Form onSubmit={handleSubmit} submitLabel="Send email" isSubmitting={processing}>
          <InputField required label="Subject" value={data.subject} onChange={(value) => setData('subject', value)} error={errors.subject} />
          <RichTextField required label="Body" value={data.body} onChange={(value) => setData('body', value)} />
        </Form>
      </section>
    </AppLayout>
  );
}
