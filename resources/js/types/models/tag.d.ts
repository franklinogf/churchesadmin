export interface Tag {
  id: number;
  name: string;
  slug: string;
  type: string;
  orderColumn: number;
  isRegular: boolean;
  createdAt: string;
  updatedAt: string;
}

export type TagRelationship = Pick<Tag, 'id' | 'name' | 'slug'>;
