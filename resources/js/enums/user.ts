export enum UserPermission {
  REGULAR_TAGS_UPDATE = 'regular_tags.update',
  REGULAR_TAGS_DELETE = 'regular_tags.delete',
  REGULAR_TAGS_CREATE = 'regular_tags.create',
  USERS_MANAGE = 'users.manage',
  USERS_CREATE = 'users.create',
  USERS_UPDATE = 'users.update',
  USERS_DELETE = 'users.delete',
  SKILLS_MANAGE = 'skills.manage',
  SKILLS_CREATE = 'skills.create',
  SKILLS_UPDATE = 'skills.update',
  SKILLS_DELETE = 'skills.delete',
  CATEGORIES_MANAGE = 'categories.manage',
  CATEGORIES_CREATE = 'categories.create',
  CATEGORIES_UPDATE = 'categories.update',
  CATEGORIES_DELETE = 'categories.delete',
  MEMBERS_MANAGE = 'members.manage',
  MEMBERS_CREATE = 'members.create',
  MEMBERS_UPDATE = 'members.update',
  MEMBERS_DELETE = 'members.delete',
  MEMBERS_FORCE_DELETE = 'members.force_delete',
  MEMBERS_RESTORE = 'members.restore',
  MISSIONARIES_MANAGE = 'missionaries.manage',
  MISSIONARIES_CREATE = 'missionaries.create',
  MISSIONARIES_UPDATE = 'missionaries.update',
  MISSIONARIES_DELETE = 'missionaries.delete',
  MISSIONARIES_FORCE_DELETE = 'missionaries.force_delete',
  MISSIONARIES_RESTORE = 'missionaries.restore',
  OFFERINGS_MANAGE = 'offerings.manage',
  OFFERINGS_CREATE = 'offerings.create',
  OFFERINGS_UPDATE = 'offerings.update',
  OFFERINGS_DELETE = 'offerings.delete',
  OFFERING_TYPES_MANAGE = 'offering_types.manage',
  OFFERING_TYPES_CREATE = 'offering_types.create',
  OFFERING_TYPES_UPDATE = 'offering_types.update',
  OFFERING_TYPES_DELETE = 'offering_types.delete',
  EXPENSE_TYPES_MANAGE = 'expense_types.manage',
  EXPENSE_TYPES_CREATE = 'expense_types.create',
  EXPENSE_TYPES_UPDATE = 'expense_types.update',
  EXPENSE_TYPES_DELETE = 'expense_types.delete',
  WALLETS_MANAGE = 'wallets.manage',
  WALLETS_CREATE = 'wallets.create',
  WALLETS_UPDATE = 'wallets.update',
  WALLETS_DELETE = 'wallets.delete',
  WALLETS_CHECK_LAYOUT_UPDATE = 'wallets.check_layout.update',
  CHECK_LAYOUTS_MANAGE = 'check_layouts.manage',
  CHECK_LAYOUTS_CREATE = 'check_layouts.create',
  CHECK_LAYOUTS_UPDATE = 'check_layouts.update',
  CHECK_LAYOUTS_DELETE = 'check_layouts.delete',
  CHECKS_MANAGE = 'checks.manage',
  CHECKS_CREATE = 'checks.create',
  CHECKS_UPDATE = 'checks.update',
  CHECKS_DELETE = 'checks.delete',
  CHECKS_CONFIRM = 'checks.confirm',
  CHECKS_PRINT = 'checks.print',
  EMAILS_MANAGE = 'emails.manage',
  EMAILS_CREATE = 'emails.create',
  EMAILS_UPDATE = 'emails.update',
  EMAILS_DELETE = 'emails.delete',
  EMAILS_SEND = 'emails.send',
  EMAILS_SEND_TO_MEMBERS = 'emails.send_to.members',
  EMAILS_SEND_TO_MISSIONARIES = 'emails.send_to.missionaries',
  VISITS_MANAGE = 'visits.manage',
  VISITS_CREATE = 'visits.create',
  VISITS_UPDATE = 'visits.update',
  VISITS_DELETE = 'visits.delete',
  VISITS_FORCE_DELETE = 'visits.force_delete',
  VISITS_RESTORE = 'visits.restore',
}

export enum UserRole {
  SUPER_ADMIN = 'super_admin',
  ADMIN = 'admin',
  SECRETARY = 'secretary',
  NO_ROLE = 'no_role',
}
