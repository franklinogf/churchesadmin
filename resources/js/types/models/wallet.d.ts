export interface Wallet {
  id: number;
  uuid: string;
  name: string;
  slug: string;
  description: string | null;
  balance: string;
  balanceInt: number;
  balanceFloat: string;
  balanceFloatNum: number;
  createdAt: string;
  updatedAt: string;
}
