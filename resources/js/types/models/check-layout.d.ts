import type { CheckLayoutField as CheckLayoutFieldName } from '@/enums/CheckLayoutField';
export type CheckFieldName = `${CheckLayoutFieldName}`;

export type CheckLayoutPosition = {
  position: {
    x: number;
    y: number;
  };
};

export type CheckLayoutField = CheckLayoutPosition & {
  target: CheckFieldName;
};

export type CheckLayout = {
  id: number;
  name: string;
  width: number;
  height: number;
  fields: CheckLayoutField[] | null;
  imageUrl: string;
  createdAt: string;
  updatedAt: string;
};

export type CheckDimensions = {
  width: number;
  height: number;
};
