<?php

declare(strict_types=1);

return [
    'payment_method' => [
        'cash' => 'Cash',
        'check' => 'Check',
    ],
    'transaction_type' => [
        'deposit' => 'Deposit',
        'withdraw' => 'Withdrawal',
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
];
