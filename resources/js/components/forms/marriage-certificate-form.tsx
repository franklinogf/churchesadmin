import MarriageCertificateController from '@/actions/App/Http/Controllers/MarriageCertificateController';
import { Form } from '@/components/forms/Form';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { TextareaField } from '@/components/forms/inputs/TextareaField';
import { FieldGroup } from '@/components/ui/field';
import type { MarriageCertificate } from '@/types/models/marriage-certificate';
import { useForm } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

type MarriageCertificateFormData = {
  book: string;
  folio: string;
  record_number: string;
  married_at: string;
  priest: string;
  groom_name: string;
  groom_age: string;
  groom_birthplace: string;
  groom_residence: string;
  groom_father_name: string;
  groom_mother_name: string;
  bride_name: string;
  bride_age: string;
  bride_birthplace: string;
  bride_residence: string;
  bride_father_name: string;
  bride_mother_name: string;
  witness1_name: string;
  witness2_name: string;
  issued_at: string;
  marginal_note: string;
};

const emptyForm: MarriageCertificateFormData = {
  book: '',
  folio: '',
  record_number: '',
  married_at: '',
  priest: '',
  groom_name: '',
  groom_age: '',
  groom_birthplace: '',
  groom_residence: '',
  groom_father_name: '',
  groom_mother_name: '',
  bride_name: '',
  bride_age: '',
  bride_birthplace: '',
  bride_residence: '',
  bride_father_name: '',
  bride_mother_name: '',
  witness1_name: '',
  witness2_name: '',
  issued_at: new Date().toISOString().substring(0, 10),
  marginal_note: '',
};

function getFormData(certificate?: MarriageCertificate): MarriageCertificateFormData {
  if (!certificate) {
    return { ...emptyForm };
  }

  return {
    book: certificate.book,
    folio: certificate.folio,
    record_number: certificate.recordNumber,
    married_at: certificate.marriedAt ?? '',
    priest: certificate.priest ?? '',
    groom_name: certificate.groomName,
    groom_age: certificate.groomAge ?? '',
    groom_birthplace: certificate.groomBirthplace ?? '',
    groom_residence: certificate.groomResidence ?? '',
    groom_father_name: certificate.groomFatherName ?? '',
    groom_mother_name: certificate.groomMotherName ?? '',
    bride_name: certificate.brideName,
    bride_age: certificate.brideAge ?? '',
    bride_birthplace: certificate.brideBirthplace ?? '',
    bride_residence: certificate.brideResidence ?? '',
    bride_father_name: certificate.brideFatherName ?? '',
    bride_mother_name: certificate.brideMotherName ?? '',
    witness1_name: certificate.witness1Name ?? '',
    witness2_name: certificate.witness2Name ?? '',
    issued_at: certificate.issuedAt ?? '',
    marginal_note: certificate.marginalNote ?? '',
  };
}

export function MarriageCertificateForm({ marriageCertificate }: { marriageCertificate?: MarriageCertificate }) {
  const { t: tPages } = useTranslation('pages');
  const { data, setData, submit, errors, processing } = useForm<MarriageCertificateFormData>(getFormData(marriageCertificate));

  function handleSubmit() {
    submit(marriageCertificate ? MarriageCertificateController.update(marriageCertificate.id) : MarriageCertificateController.store(), {
      preserveScroll: true,
    });
  }

  return (
    <Form onSubmit={handleSubmit} isSubmitting={processing} className="w-full max-w-4xl">
      <FieldGroup>
        <FieldsGrid>
          <InputField
            required
            label={tPages(($) => $.main.books.marriageCertificate.form.book)}
            value={data.book}
            onChange={(value) => setData('book', value)}
            error={errors.book}
          />
          <InputField
            required
            label={tPages(($) => $.main.books.marriageCertificate.form.folio)}
            value={data.folio}
            onChange={(value) => setData('folio', value)}
            error={errors.folio}
          />
          <InputField
            required
            label={tPages(($) => $.main.books.marriageCertificate.form.recordNumber)}
            value={data.record_number}
            onChange={(value) => setData('record_number', value)}
            error={errors.record_number}
          />
        </FieldsGrid>

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.marriageCertificate.form.priest)}
            value={data.priest}
            onChange={(value) => setData('priest', value)}
            error={errors.priest}
          />
          <DateField
            label={tPages(($) => $.main.books.marriageCertificate.form.marriedAt)}
            value={data.married_at}
            onChange={(value) => setData('married_at', value ?? '')}
            error={errors.married_at}
          />
        </FieldsGrid>

        <p className="text-sm font-medium">{tPages(($) => $.main.books.marriageCertificate.form.groomSection)}</p>

        <InputField
          required
          label={tPages(($) => $.main.books.marriageCertificate.form.groomName)}
          value={data.groom_name}
          onChange={(value) => setData('groom_name', value)}
          error={errors.groom_name}
        />

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.marriageCertificate.form.groomAge)}
            value={data.groom_age}
            onChange={(value) => setData('groom_age', value)}
            error={errors.groom_age}
          />
          <InputField
            label={tPages(($) => $.main.books.marriageCertificate.form.groomBirthplace)}
            value={data.groom_birthplace}
            onChange={(value) => setData('groom_birthplace', value)}
            error={errors.groom_birthplace}
          />
          <InputField
            label={tPages(($) => $.main.books.marriageCertificate.form.groomResidence)}
            value={data.groom_residence}
            onChange={(value) => setData('groom_residence', value)}
            error={errors.groom_residence}
          />
        </FieldsGrid>

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.marriageCertificate.form.groomFatherName)}
            value={data.groom_father_name}
            onChange={(value) => setData('groom_father_name', value)}
            error={errors.groom_father_name}
          />
          <InputField
            label={tPages(($) => $.main.books.marriageCertificate.form.groomMotherName)}
            value={data.groom_mother_name}
            onChange={(value) => setData('groom_mother_name', value)}
            error={errors.groom_mother_name}
          />
        </FieldsGrid>

        <p className="text-sm font-medium">{tPages(($) => $.main.books.marriageCertificate.form.brideSection)}</p>

        <InputField
          required
          label={tPages(($) => $.main.books.marriageCertificate.form.brideName)}
          value={data.bride_name}
          onChange={(value) => setData('bride_name', value)}
          error={errors.bride_name}
        />

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.marriageCertificate.form.brideAge)}
            value={data.bride_age}
            onChange={(value) => setData('bride_age', value)}
            error={errors.bride_age}
          />
          <InputField
            label={tPages(($) => $.main.books.marriageCertificate.form.brideBirthplace)}
            value={data.bride_birthplace}
            onChange={(value) => setData('bride_birthplace', value)}
            error={errors.bride_birthplace}
          />
          <InputField
            label={tPages(($) => $.main.books.marriageCertificate.form.brideResidence)}
            value={data.bride_residence}
            onChange={(value) => setData('bride_residence', value)}
            error={errors.bride_residence}
          />
        </FieldsGrid>

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.marriageCertificate.form.brideFatherName)}
            value={data.bride_father_name}
            onChange={(value) => setData('bride_father_name', value)}
            error={errors.bride_father_name}
          />
          <InputField
            label={tPages(($) => $.main.books.marriageCertificate.form.brideMotherName)}
            value={data.bride_mother_name}
            onChange={(value) => setData('bride_mother_name', value)}
            error={errors.bride_mother_name}
          />
        </FieldsGrid>

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.marriageCertificate.form.witness1Name)}
            value={data.witness1_name}
            onChange={(value) => setData('witness1_name', value)}
            error={errors.witness1_name}
          />
          <InputField
            label={tPages(($) => $.main.books.marriageCertificate.form.witness2Name)}
            value={data.witness2_name}
            onChange={(value) => setData('witness2_name', value)}
            error={errors.witness2_name}
          />
        </FieldsGrid>

        <DateField
          label={tPages(($) => $.main.books.marriageCertificate.form.issuedAt)}
          value={data.issued_at}
          onChange={(value) => setData('issued_at', value ?? '')}
          error={errors.issued_at}
        />

        <TextareaField
          label={tPages(($) => $.main.books.marriageCertificate.form.marginalNote)}
          value={data.marginal_note}
          onChange={(event) => setData('marginal_note', event.target.value)}
          error={errors.marginal_note}
        />
      </FieldGroup>
    </Form>
  );
}
