import BaptismCertificateController from '@/actions/App/Http/Controllers/BaptismCertificateController';
import { Form } from '@/components/forms/Form';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { TextareaField } from '@/components/forms/inputs/TextareaField';
import { FieldGroup } from '@/components/ui/field';
import type { BaptismCertificate } from '@/types/models/baptism-certificate';
import { useForm } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

type BaptismCertificateFormData = {
  book: string;
  folio: string;
  record_number: string;
  baptized_name: string;
  baptized_at: string;
  priest: string;
  birth_place: string;
  birth_date: string;
  father_name: string;
  father_origin_place: string;
  father_residence_place: string;
  mother_name: string;
  mother_origin_place: string;
  mother_residence_place: string;
  paternal_grandfather_name: string;
  paternal_grandmother_name: string;
  maternal_grandfather_name: string;
  maternal_grandmother_name: string;
  godfather_name: string;
  godmother_name: string;
  issued_place: string;
  issued_at: string;
  marginal_note: string;
};

const emptyForm: BaptismCertificateFormData = {
  book: '',
  folio: '',
  record_number: '',
  baptized_name: '',
  baptized_at: '',
  priest: '',
  birth_place: '',
  birth_date: '',
  father_name: '',
  father_origin_place: '',
  father_residence_place: '',
  mother_name: '',
  mother_origin_place: '',
  mother_residence_place: '',
  paternal_grandfather_name: '',
  paternal_grandmother_name: '',
  maternal_grandfather_name: '',
  maternal_grandmother_name: '',
  godfather_name: '',
  godmother_name: '',
  issued_place: '',
  issued_at: new Date().toISOString().substring(0, 10),
  marginal_note: '',
};

function getFormData(certificate?: BaptismCertificate): BaptismCertificateFormData {
  if (!certificate) {
    return { ...emptyForm };
  }

  return {
    book: certificate.book,
    folio: certificate.folio,
    record_number: certificate.recordNumber,
    baptized_name: certificate.baptizedName,
    baptized_at: certificate.baptizedAt ?? '',
    priest: certificate.priest ?? '',
    birth_place: certificate.birthPlace ?? '',
    birth_date: certificate.birthDate ?? '',
    father_name: certificate.fatherName ?? '',
    father_origin_place: certificate.fatherOriginPlace ?? '',
    father_residence_place: certificate.fatherResidencePlace ?? '',
    mother_name: certificate.motherName ?? '',
    mother_origin_place: certificate.motherOriginPlace ?? '',
    mother_residence_place: certificate.motherResidencePlace ?? '',
    paternal_grandfather_name: certificate.paternalGrandfatherName ?? '',
    paternal_grandmother_name: certificate.paternalGrandmotherName ?? '',
    maternal_grandfather_name: certificate.maternalGrandfatherName ?? '',
    maternal_grandmother_name: certificate.maternalGrandmotherName ?? '',
    godfather_name: certificate.godfatherName ?? '',
    godmother_name: certificate.godmotherName ?? '',
    issued_place: certificate.issuedPlace ?? '',
    issued_at: certificate.issuedAt ?? '',
    marginal_note: certificate.marginalNote ?? '',
  };
}

export function BaptismCertificateForm({ baptismCertificate }: { baptismCertificate?: BaptismCertificate }) {
  const { t: tPages } = useTranslation('pages');
  const { data, setData, submit, errors, processing } = useForm<BaptismCertificateFormData>(getFormData(baptismCertificate));

  function handleSubmit() {
    submit(baptismCertificate ? BaptismCertificateController.update(baptismCertificate.id) : BaptismCertificateController.store(), {
      preserveScroll: true,
    });
  }

  return (
    <Form onSubmit={handleSubmit} isSubmitting={processing} className="w-full max-w-4xl">
      <FieldGroup>
        <FieldsGrid>
          <InputField
            required
            label={tPages(($) => $.main.books.baptismCertificate.form.book)}
            value={data.book}
            onChange={(value) => setData('book', value)}
            error={errors.book}
          />
          <InputField
            required
            label={tPages(($) => $.main.books.baptismCertificate.form.folio)}
            value={data.folio}
            onChange={(value) => setData('folio', value)}
            error={errors.folio}
          />
          <InputField
            required
            label={tPages(($) => $.main.books.baptismCertificate.form.recordNumber)}
            value={data.record_number}
            onChange={(value) => setData('record_number', value)}
            error={errors.record_number}
          />
        </FieldsGrid>

        <InputField
          required
          label={tPages(($) => $.main.books.baptismCertificate.form.baptizedName)}
          value={data.baptized_name}
          onChange={(value) => setData('baptized_name', value)}
          error={errors.baptized_name}
        />

        <FieldsGrid>
          <DateField
            label={tPages(($) => $.main.books.baptismCertificate.form.baptizedAt)}
            value={data.baptized_at}
            onChange={(value) => setData('baptized_at', value ?? '')}
            error={errors.baptized_at}
          />
          <InputField
            label={tPages(($) => $.main.books.baptismCertificate.form.priest)}
            value={data.priest}
            onChange={(value) => setData('priest', value)}
            error={errors.priest}
          />
        </FieldsGrid>

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.baptismCertificate.form.birthPlace)}
            value={data.birth_place}
            onChange={(value) => setData('birth_place', value)}
            error={errors.birth_place}
          />
          <DateField
            label={tPages(($) => $.main.books.baptismCertificate.form.birthDate)}
            value={data.birth_date}
            onChange={(value) => setData('birth_date', value ?? '')}
            error={errors.birth_date}
          />
        </FieldsGrid>

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.baptismCertificate.form.fatherName)}
            value={data.father_name}
            onChange={(value) => setData('father_name', value)}
            error={errors.father_name}
          />
          <InputField
            label={tPages(($) => $.main.books.baptismCertificate.form.fatherOriginPlace)}
            value={data.father_origin_place}
            onChange={(value) => setData('father_origin_place', value)}
            error={errors.father_origin_place}
          />
          <InputField
            label={tPages(($) => $.main.books.baptismCertificate.form.fatherResidencePlace)}
            value={data.father_residence_place}
            onChange={(value) => setData('father_residence_place', value)}
            error={errors.father_residence_place}
          />
        </FieldsGrid>

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.baptismCertificate.form.motherName)}
            value={data.mother_name}
            onChange={(value) => setData('mother_name', value)}
            error={errors.mother_name}
          />
          <InputField
            label={tPages(($) => $.main.books.baptismCertificate.form.motherOriginPlace)}
            value={data.mother_origin_place}
            onChange={(value) => setData('mother_origin_place', value)}
            error={errors.mother_origin_place}
          />
          <InputField
            label={tPages(($) => $.main.books.baptismCertificate.form.motherResidencePlace)}
            value={data.mother_residence_place}
            onChange={(value) => setData('mother_residence_place', value)}
            error={errors.mother_residence_place}
          />
        </FieldsGrid>

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.baptismCertificate.form.paternalGrandfatherName)}
            value={data.paternal_grandfather_name}
            onChange={(value) => setData('paternal_grandfather_name', value)}
            error={errors.paternal_grandfather_name}
          />
          <InputField
            label={tPages(($) => $.main.books.baptismCertificate.form.paternalGrandmotherName)}
            value={data.paternal_grandmother_name}
            onChange={(value) => setData('paternal_grandmother_name', value)}
            error={errors.paternal_grandmother_name}
          />
        </FieldsGrid>

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.baptismCertificate.form.maternalGrandfatherName)}
            value={data.maternal_grandfather_name}
            onChange={(value) => setData('maternal_grandfather_name', value)}
            error={errors.maternal_grandfather_name}
          />
          <InputField
            label={tPages(($) => $.main.books.baptismCertificate.form.maternalGrandmotherName)}
            value={data.maternal_grandmother_name}
            onChange={(value) => setData('maternal_grandmother_name', value)}
            error={errors.maternal_grandmother_name}
          />
        </FieldsGrid>

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.baptismCertificate.form.godfatherName)}
            value={data.godfather_name}
            onChange={(value) => setData('godfather_name', value)}
            error={errors.godfather_name}
          />
          <InputField
            label={tPages(($) => $.main.books.baptismCertificate.form.godmotherName)}
            value={data.godmother_name}
            onChange={(value) => setData('godmother_name', value)}
            error={errors.godmother_name}
          />
        </FieldsGrid>

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.baptismCertificate.form.issuedPlace)}
            value={data.issued_place}
            onChange={(value) => setData('issued_place', value)}
            error={errors.issued_place}
          />
          <DateField
            label={tPages(($) => $.main.books.baptismCertificate.form.issuedAt)}
            value={data.issued_at}
            onChange={(value) => setData('issued_at', value ?? '')}
            error={errors.issued_at}
          />
        </FieldsGrid>

        <TextareaField
          label={tPages(($) => $.main.books.baptismCertificate.form.marginalNote)}
          value={data.marginal_note}
          onChange={(event) => setData('marginal_note', event.target.value)}
          error={errors.marginal_note}
        />
      </FieldGroup>
    </Form>
  );
}
