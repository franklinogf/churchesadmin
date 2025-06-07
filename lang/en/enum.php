<?php

declare(strict_types=1);

return [
    'follow_up_type' => [
        'call' => 'Call',
        'email' => 'Email',
        'in_person' => 'In Person',
        'letter' => 'Letter',
    ],
    'email_status' => [
        'pending' => 'Pending',
        'sent' => 'Sent',
        'failed' => 'Failed',
        'sending' => 'Sending',
    ],
    'model_morph_name' => [
        'member' => 'Member',
        'missionary' => 'Missionary',
        'user' => 'User',
        'church' => 'Church',
        'church_wallet' => 'Wallet',
        'offering_type' => 'Offering Type',
        'check_layout' => 'Check Layout',
        'email' => 'Email',
        'visit' => 'Visit',
    ],
    'wallet_name' => [
        'primary' => 'Primary',
    ],
    'payment_method' => [
        'cash' => 'Cash',
        'check' => 'Check',
    ],
    'transaction_type' => [
        'deposit' => 'Deposit',
        'withdraw' => 'Withdrawal',
        'previous_balance' => 'Previous Balance',
    ],
    'transaction_meta_type' => [
        'initial' => 'Initial',
        'check' => 'Check',
        'offering' => 'Offering',
        'expense' => 'Expense',
    ],
    'civil_status' => [
        'single' => 'Single',
        'married' => 'Married',
        'divorced' => 'Divorced',
        'widowed' => 'Widowed',
        'separated' => 'Separated',
    ],
    'gender' => [
        'male' => 'Male',
        'female' => 'Female',
    ],
    'language_code' => [
        'en' => 'English',
        'es' => 'Spanish',
    ],
    'check_type' => [
        'payment' => 'Payment',
        'refund' => 'Refund',
    ],
    'offering_frequency' => [
        'weekly' => 'Every week',
        'bi_weekly' => 'Every two weeks',
        'monthly' => 'Every month',
        'bi_monthly' => 'Every two months',
        'quarterly' => 'Every three months',
        'semi_annually' => 'Every six months',
        'annually' => 'Every year',
        'one_time' => 'One time only',
    ],
    'tag_type' => [
        'skill' => 'Skill',
        'category' => 'Category',
    ],
    'tenant_role' => [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'secretary' => 'Secretary',
        'no_role' => 'No Role',
    ],
    'tenant_permission' => [
        'regular_tags' => [
            'update' => 'Update Regular Tags',
            'delete' => 'Delete Regular Tags',
            'create' => 'Create Regular Tags',
        ],
        'users' => [
            'manage' => 'Manage Users',
            'create' => 'Create Users',
            'update' => 'Update Users',
            'delete' => 'Delete Users',
        ],
        'skills' => [
            'manage' => 'Manage Skills',
            'create' => 'Create Skills',
            'update' => 'Update Skills',
            'delete' => 'Delete Skills',
        ],
        'categories' => [
            'manage' => 'Manage Categories',
            'create' => 'Create Categories',
            'update' => 'Update Categories',
            'delete' => 'Delete Categories',
        ],
        'members' => [
            'manage' => 'Manage Members',
            'create' => 'Create Members',
            'update' => 'Update Members',
            'delete' => 'Delete Members',
            'force_delete' => 'Force Delete Members',
            'restore' => 'Restore Members',
        ],
        'missionaries' => [
            'manage' => 'Manage Missionaries',
            'create' => 'Create Missionaries',
            'update' => 'Update Missionaries',
            'delete' => 'Delete Missionaries',
            'force_delete' => 'Force Delete Missionaries',
            'restore' => 'Restore Missionaries',
        ],
        'offerings' => [
            'manage' => 'Manage Offerings',
            'create' => 'Create Offerings',
            'update' => 'Update Offerings',
            'delete' => 'Delete Offerings',
        ],
        'offering_types' => [
            'manage' => 'Manage Offering Types',
            'create' => 'Create Offering Types',
            'update' => 'Update Offering Types',
            'delete' => 'Delete Offering Types',
        ],
        'expense_types' => [
            'manage' => 'Manage Expense Types',
            'create' => 'Create Expense Types',
            'update' => 'Update Expense Types',
            'delete' => 'Delete Expense Types',
        ],
        'wallets' => [
            'manage' => 'Manage Wallets',
            'create' => 'Create Wallets',
            'update' => 'Update Wallets',
            'delete' => 'Delete Wallets',
            'check_layout' => [
                'update' => 'Update Wallet Check Layouts',
            ],
        ],
        'check_layouts' => [
            'manage' => 'Manage Check Layouts',
            'create' => 'Create Check Layouts',
            'update' => 'Update Check Layouts',
            'delete' => 'Delete Check Layouts',
        ],
        'checks' => [
            'manage' => 'Manage Checks',
            'create' => 'Create Checks',
            'update' => 'Update Checks',
            'delete' => 'Delete Checks',
            'confirm' => 'Confirm Checks',
            'print' => 'Print Checks',
        ],
        'emails' => [
            'manage' => 'Manage Emails',
            'create' => 'Create Emails',
            'update' => 'Update Emails',
            'delete' => 'Delete Emails',
            'send' => 'Send Emails',
            'send_to' => [
                'members' => 'Send Emails to Members',
                'missionaries' => 'Send Emails to Missionaries',
            ],
        ],
        'visits' => [
            'manage' => 'Manage Visits',
            'create' => 'Create Visits',
            'update' => 'Update Visits',
            'delete' => 'Delete Visits',
            'force_delete' => 'Force Delete Visits',
            'restore' => 'Restore Visits',
        ],
    ],
];
