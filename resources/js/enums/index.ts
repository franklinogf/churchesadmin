export enum Gender {
  MALE = 'male',
  FEMALE = 'female',
}
export enum CivilStatus {
  SINGLE = 'single',
  MARRIED = 'married',
  DIVORCED = 'divorced',
  WIDOWED = 'widowed',
  SEPARATED = 'separated',
}
export enum LanguageCode {
  EN = 'en',
  ES = 'es',
}
export enum OfferingFrequency {
  ONE_TIME = 'one_time',
  WEEKLY = 'weekly',
  BIWEEKLY = 'biweekly',
  MONTHLY = 'monthly',
  BIMONTHLY = 'bimonthly',
  QUARTERLY = 'quarterly',
  SEMIANNUALLY = 'semiannually',
  ANNUALLY = 'annually',
}

export enum WalletName {
  PRIMARY = 'primary',
}

export enum PaymentMethod {
  CASH = 'cash',
  CHECK = 'check',
}

export enum CheckType {
  PAYMENT = 'payment',
  REFUND = 'refund',
}

export enum TransactionType {
  DEPOSIT = 'deposit',
  WITHDRAW = 'withdraw',
}

export enum TransactionMetaType {
  INITIAL = 'initial',
  CHECK = 'check',
  OFFERING = 'offering',
  EXPENSE = 'expense',
}

export enum CheckLayoutFieldName {
  PAYEE = 'payee',
  AMOUNT = 'amount',
  AMOUNT_WORDS = 'amount_in_words',
  DATE = 'date',
  MEMO = 'memo',
}
