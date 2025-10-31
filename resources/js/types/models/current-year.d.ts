export interface CurrentYear {
  id: number;
  year: string;
  startDate: string;
  endDate: string;
  isCurrent: boolean;
  createdAt: string;
  updatedAt: string;
  previousYear: CurrentYear | null;
}
