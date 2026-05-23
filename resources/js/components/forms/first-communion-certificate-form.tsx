import FirstCommunionCertificateController from '@/actions/App/Http/Controllers/FirstCommunionCertificateController';
import { Form } from '@/components/forms/Form';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { FieldGroup } from '@/components/ui/field';
import type { FirstCommunionCertificate } from '@/types/models/first-communion-certificate';
import { useForm } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

type FirstCommunionCertificateFormData = {
  priest: string;
  communicant_name: string;
  father_name: string;
  mother_name: string;
  communion_at: string;
  issued_at: string;
};

const emptyForm: FirstCommunionCertificateFormData = {
  priest: '',
  communicant_name: '',
  father_name: '',
  mother_name: '',
  communion_at: '',
  issued_at: new Date().toISOString().substring(0, 10),
};

function getFormData(certificate?: FirstCommunionCertificate): FirstCommunionCertificateFormData {
  if (!certificate) {
    return { ...emptyForm };
  }

  return {
    priest: certificate.priest ?? '',
    communicant_name: certificate.communicantName,
    father_name: certificate.fatherName ?? '',
    mother_name: certificate.motherName ?? '',
    communion_at: certificate.communionAt ?? '',
    issued_at: certificate.issuedAt ?? '',
  };
}

export function FirstCommunionCertificateForm({ firstCommunionCertificate }: { firstCommunionCertificate?: FirstCommunionCertificate }) {
  const { t: tPages } = useTranslation('pages');
  const { data, setData, submit, errors, processing } = useForm<FirstCommunionCertificateFormData>(getFormData(firstCommunionCertificate));

  function handleSubmit() {
    submit(
      firstCommunionCertificate
        ? FirstCommunionCertificateController.update(firstCommunionCertificate.id)
        : FirstCommunionCertificateController.store(),
      { preserveScroll: true },
    );
  }

  return (
    <Form onSubmit={handleSubmit} isSubmitting={processing} className="w-full max-w-4xl">
      <FieldGroup>
        <InputField
          required
          label={tPages(($) => $.main.books.communionCertificate.form.communicantName)}
          value={data.communicant_name}
          onChange={(value) => setData('communicant_name', value)}
          error={errors.communicant_name}
        />

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.communionCertificate.form.fatherName)}
            value={data.father_name}
            onChange={(value) => setData('father_name', value)}
            error={errors.father_name}
          />
          <InputField
            label={tPages(($) => $.main.books.communionCertificate.form.motherName)}
            value={data.mother_name}
            onChange={(value) => setData('mother_name', value)}
            error={errors.mother_name}
          />
        </FieldsGrid>

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.communionCertificate.form.priest)}
            value={data.priest}
            onChange={(value) => setData('priest', value)}
            error={errors.priest}
          />
          <DateField
            label={tPages(($) => $.main.books.communionCertificate.form.communionAt)}
            value={data.communion_at}
            onChange={(value) => setData('communion_at', value ?? '')}
            error={errors.communion_at}
          />
          <DateField
            label={tPages(($) => $.main.books.communionCertificate.form.issuedAt)}
            value={data.issued_at}
            onChange={(value) => setData('issued_at', value ?? '')}
            error={errors.issued_at}
          />
        </FieldsGrid>
      </FieldGroup>
    </Form>
  );
}
