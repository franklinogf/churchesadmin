import type { CheckLayoutFieldName } from '@/enums';

export type CheckFieldName = `${CheckLayoutFieldName}`;

export type CheckPosition = {
  position: {
    x: number;
    y: number;
  };
};
export type CheckLayoutFields = Record<CheckFieldName, CheckPosition>;
export type CheckLayout = {
  id: number;
  name: string;
  width: number;
  height: number;
  fields: CheckLayoutFields;
  imageUrl: string;
  createdAt: string;
  updatedAt: string;
};

export type CheckDimensions = {
  width: number;
  height: number;
};
