export type FirstCommunionCertificate = {
  id: number;
  priest: string | null;
  communicantName: string;
  fatherName: string | null;
  motherName: string | null;
  communionAt: string | null;
  issuedAt: string | null;
  createdAt: string;
  updatedAt: string;
};
