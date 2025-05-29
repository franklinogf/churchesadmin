// @ts-expect-error needed for generics
// eslint-disable-next-line
type MergeTypes<TypesArray extends any[], Res = {}> = TypesArray extends [infer Head, ...infer Rem] ? MergeTypes<Rem, Res & Head> : Res;

type OnlyFirst<F, S> = F & { [Key in keyof Omit<S, keyof F>]?: never };

// eslint-disable-next-line
export type OneOf<TypesArray extends any[], Res = never, AllProperties = MergeTypes<TypesArray>> = TypesArray extends [infer Head, ...infer Rem]
  ? OneOf<Rem, Res | OnlyFirst<Head, AllProperties>, AllProperties>
  : Res;

export type AutoComplete<T> = T | (string & {});

export type MakeRequired<T, K extends keyof T> = T & Required<Pick<T, K>>;
