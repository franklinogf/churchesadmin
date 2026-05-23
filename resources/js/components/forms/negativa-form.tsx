import NegativaController from '@/actions/App/Http/Controllers/NegativaController';
import { Form } from '@/components/forms/Form';
import { DateField } from '@/components/forms/inputs/DateField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { FieldGroup } from '@/components/ui/field';
import type { Negativa } from '@/types/models/negativa';
import { useForm } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

type NegativaFormData = {
  person_name: string;
  father_name: string;
  mother_name: string;
  searched_from: string;
  searched_to: string;
  priest: string;
  issued_at: string;
};

const emptyForm: NegativaFormData = {
  person_name: '',
  father_name: '',
  mother_name: '',
  searched_from: '',
  searched_to: '',
  priest: '',
  issued_at: new Date().toISOString().substring(0, 10),
};

function getFormData(negativa?: Negativa): NegativaFormData {
  if (!negativa) {
    return { ...emptyForm };
  }

  return {
    person_name: negativa.personName,
    father_name: negativa.fatherName ?? '',
    mother_name: negativa.motherName ?? '',
    searched_from: negativa.searchedFrom ?? '',
    searched_to: negativa.searchedTo ?? '',
    priest: negativa.priest ?? '',
    issued_at: negativa.issuedAt ?? '',
  };
}

export function NegativaForm({ negativa }: { negativa?: Negativa }) {
  const { t: tPages } = useTranslation('pages');
  const { data, setData, submit, errors, processing } = useForm<NegativaFormData>(getFormData(negativa));

  function handleSubmit() {
    submit(negativa ? NegativaController.update(negativa.id) : NegativaController.store(), {
      preserveScroll: true,
    });
  }

  return (
    <Form onSubmit={handleSubmit} isSubmitting={processing} className="w-full max-w-4xl">
      <FieldGroup>
        <InputField
          required
          label={tPages(($) => $.main.books.negativa.form.personName)}
          value={data.person_name}
          onChange={(value) => setData('person_name', value)}
          error={errors.person_name}
        />

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.negativa.form.fatherName)}
            value={data.father_name}
            onChange={(value) => setData('father_name', value)}
            error={errors.father_name}
          />
          <InputField
            label={tPages(($) => $.main.books.negativa.form.motherName)}
            value={data.mother_name}
            onChange={(value) => setData('mother_name', value)}
            error={errors.mother_name}
          />
        </FieldsGrid>

        <FieldsGrid>
          <DateField
            label={tPages(($) => $.main.books.negativa.form.searchedFrom)}
            value={data.searched_from}
            onChange={(value) => setData('searched_from', value ?? '')}
            error={errors.searched_from}
          />
          <DateField
            label={tPages(($) => $.main.books.negativa.form.searchedTo)}
            value={data.searched_to}
            onChange={(value) => setData('searched_to', value ?? '')}
            error={errors.searched_to}
          />
        </FieldsGrid>

        <FieldsGrid>
          <InputField
            label={tPages(($) => $.main.books.negativa.form.priest)}
            value={data.priest}
            onChange={(value) => setData('priest', value)}
            error={errors.priest}
          />
          <DateField
            label={tPages(($) => $.main.books.negativa.form.issuedAt)}
            value={data.issued_at}
            onChange={(value) => setData('issued_at', value ?? '')}
            error={errors.issued_at}
          />
        </FieldsGrid>
      </FieldGroup>
    </Form>
  );
}
