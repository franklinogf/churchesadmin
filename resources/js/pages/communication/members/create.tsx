import { Form } from '@/components/forms/Form';
import { InputField } from '@/components/forms/inputs/InputField';
import { RichTextField } from '@/components/forms/inputs/RichTextField';
import { PageTitle } from '@/components/PageTitle';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/app-layout';
import { useForm } from '@inertiajs/react';

interface Props {
  membersAmount: number;
  membersId: string[];
}

type EmailForm = {
  subject: string;
  body: string;
  media: File[];
};

export default function Create({ membersAmount }: Props) {
  const { data, setData, errors, processing, post } = useForm<EmailForm>({
    subject: '',
    body: '',
    media: [],
  });

  function handleSubmit() {
    post(route('messages.members.store'));
  }

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
          <Input type="file" multiple accept="image/*" onChange={(e) => setData('media', Array.from(e.target.files || []))} />
        </Form>
      </section>
    </AppLayout>
  );
}
