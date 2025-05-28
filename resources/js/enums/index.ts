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
  ONETIME = 'one_time',
  WEEKLY = 'weekly',
  BIWEEKLY = 'bi_weekly',
  MONTHLY = 'monthly',
  BIMONTHLY = 'bi_monthly',
  QUARTERLY = 'quarterly',
  SEMIANNUALLY = 'semi_annually',
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

export enum SessionName {
  EMAIL_RECIPIENTS = 'email_recipients',
}

export enum ModelMorphName {
  MEMBER = 'member',
  MISSIONARY = 'missionary',
  OFFERING = 'offering',
  EXPENSE = 'expense',
  CHECK = 'check',
  TRANSACTION = 'transaction',
  WALLET = 'wallet',
}

export enum EmailStatus {
  SENT = 'sent',
  FAILED = 'failed',
  PENDING = 'pending',
  SENDING = 'sending',
}
