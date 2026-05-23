export type ConfirmationCertificate = {
  id: number;
  book: string;
  folio: string;
  recordNumber: string;
  priest: string | null;
  confirmedName: string;
  fatherName: string | null;
  motherName: string | null;
  confirmedBy: string | null;
  confirmedAt: string | null;
  godfatherName: string | null;
  godmotherName: string | null;
  issuedPlace: string | null;
  issuedAt: string | null;
  marginalNote: string | null;
  createdAt: string;
  updatedAt: string;
};
