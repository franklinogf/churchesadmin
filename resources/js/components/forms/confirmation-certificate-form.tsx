import ConfirmationCertificateController from '@/actions/App/Http/Controllers/ConfirmationCertificateController';
import { Form } from '@/components/forms/Form';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { TextareaField } from '@/components/forms/inputs/TextareaField';
import { FieldGroup } from '@/components/ui/field';
import type { ConfirmationCertificate } from '@/types/models/confirmation-certificate';
import { useForm } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

type ConfirmationCertificateFormData = {
  book: string;
  folio: string;
  record_number: string;
  priest: string;
  confirmed_name: string;
  father_name: string;
  mother_name: string;
  confirmed_by: string;
  confirmed_at: string;
  godfather_name: string;
  godmother_name: string;
  issued_place: string;
  issued_at: string;
  marginal_note: string;
};

const emptyForm: ConfirmationCertificateFormData = {
  book: '',
  folio: '',
  record_number: '',
  priest: '',
  confirmed_name: '',
  father_name: '',
  mother_name: '',
  confirmed_by: '',
  confirmed_at: '',
  godfather_name: '',
  godmother_name: '',
  issued_place: '',
  issued_at: new Date().toISOString().substring(0, 10),
  marginal_note: '',
};

function getFormData(certificate?: ConfirmationCertificate): ConfirmationCertificateFormData {
  if (!certificate) {
    return { ...emptyForm };
  }

  return {
    book: certificate.book,
    folio: certificate.folio,
    record_number: certificate.recordNumber,
    priest: certificate.priest ?? '',
    confirmed_name: certificate.confirmedName,
    father_name: certificate.fatherName ?? '',
    mother_name: certificate.motherName ?? '',
    confirmed_by: certificate.confirmedBy ?? '',
    confirmed_at: certificate.confirmedAt ?? '',
    godfather_name: certificate.godfatherName ?? '',
    godmother_name: certificate.godmotherName ?? '',
    issued_place: certificate.issuedPlace ?? '',
    issued_at: certificate.issuedAt ?? '',
    marginal_note: certificate.marginalNote ?? '',
  };
}

export function ConfirmationCertificateForm({ confirmationCertificate }: { confirmationCertificate?: ConfirmationCertificate }) {
  const { t: tPages } = useTranslation('pages');
  const { data, setData, submit, errors, processing } = useForm<ConfirmationCertificateFormData>(getFormData(confirmationCertificate));

  function handleSubmit() {
    submit(
      confirmationCertificate ? ConfirmationCertificateController.update(confirmationCertificate.id) : ConfirmationCertificateController.store(),
      { preserveScroll: true },
    );
  }

  return (
    <Form onSubmit={handleSubmit} isSubmitting={processing} className="w-full max-w-4xl">
      <FieldGroup>
        <FieldsGrid>
          <InputField
            required
            label={tPages(($) => $.main.books.confirmationCertificate.form.book)}
            value={data.book}
            onChange={(value) => setData('book', value)}
            error={errors.book}
          />
          <InputField
            required
            label={tPages(($) => $.main.books.confirmationCertificate.form.folio)}
            value={data.folio}
            onChange={(value) => setData('folio', value)}
            error={errors.folio}
          />
          <InputField
            required
            label={tPages(($) => $.main.books.confirmationCertificate.form.recordNumber)}
            value={data.record_number}
            onChange={(value) => setData('record_number', value)}
            error={errors.record_number}
          />
        </FieldsGrid>

        <InputField
          required
          label={tPages(($) => $.main.books.confirmationCertificate.form.confirmedName)}
          value={data.confirmed_name}
          onChange={(value) => setData('confirmed_name', value)}
          error={errors.confirmed_name}
        />

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.confirmationCertificate.form.priest)}
            value={data.priest}
            onChange={(value) => setData('priest', value)}
            error={errors.priest}
          />
          <InputField
            label={tPages(($) => $.main.books.confirmationCertificate.form.confirmedBy)}
            value={data.confirmed_by}
            onChange={(value) => setData('confirmed_by', value)}
            error={errors.confirmed_by}
          />
          <DateField
            label={tPages(($) => $.main.books.confirmationCertificate.form.confirmedAt)}
            value={data.confirmed_at}
            onChange={(value) => setData('confirmed_at', value ?? '')}
            error={errors.confirmed_at}
          />
        </FieldsGrid>

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.confirmationCertificate.form.fatherName)}
            value={data.father_name}
            onChange={(value) => setData('father_name', value)}
            error={errors.father_name}
          />
          <InputField
            label={tPages(($) => $.main.books.confirmationCertificate.form.motherName)}
            value={data.mother_name}
            onChange={(value) => setData('mother_name', value)}
            error={errors.mother_name}
          />
        </FieldsGrid>

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.confirmationCertificate.form.godfatherName)}
            value={data.godfather_name}
            onChange={(value) => setData('godfather_name', value)}
            error={errors.godfather_name}
          />
          <InputField
            label={tPages(($) => $.main.books.confirmationCertificate.form.godmotherName)}
            value={data.godmother_name}
            onChange={(value) => setData('godmother_name', value)}
            error={errors.godmother_name}
          />
        </FieldsGrid>

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.confirmationCertificate.form.issuedPlace)}
            value={data.issued_place}
            onChange={(value) => setData('issued_place', value)}
            error={errors.issued_place}
          />
          <DateField
            label={tPages(($) => $.main.books.confirmationCertificate.form.issuedAt)}
            value={data.issued_at}
            onChange={(value) => setData('issued_at', value ?? '')}
            error={errors.issued_at}
          />
        </FieldsGrid>

        <TextareaField
          label={tPages(($) => $.main.books.confirmationCertificate.form.marginalNote)}
          value={data.marginal_note}
          onChange={(event) => setData('marginal_note', event.target.value)}
          error={errors.marginal_note}
        />
      </FieldGroup>
    </Form>
  );
}
