<?php

declare(strict_types=1);

return [
    'success' => 'Success',
    'error' => 'Error',
    'warning' => 'Warning',
    'info' => 'Info',
    'notice' => 'Notice',
    'alert' => 'Alert',
    'message' => [
        'created' => ':model created successfully.',
        'updated' => ':model updated successfully.',
        'deleted' => ':model deleted successfully.',
        'restored' => ':model restored successfully.',
        'archived' => ':model archived successfully.',
        'unarchived' => ':model unarchived successfully.',
        'activated' => ':model activated successfully.',
        'deactivated' => ':model deactivated successfully.',
        'imported' => ':model imported successfully.',
        'exported' => ':model exported successfully.',
        'synced' => ':model synced successfully.',
        'wallet' => [
            'insufficient_funds' => 'Insufficient funds in :wallet.',
            'empty_balance' => 'The balance of :wallet is empty.',
            'not_found' => 'Wallet not found or has been removed.',
            'invalid_amount' => 'Invalid amount given.',
            'transaction_failed' => 'Transaction failed.',
            'already_confirmed' => 'Transaction has already been confirmed.',
        ],
        'check' => [
            'number_generated' => 'Checks numbers generated successfully.',
            'number_exists' => 'The following check number already exist: :numbers|The following check numbers already exist: :numbers',
            'confirmed' => 'Checks confirmed successfully.',
        ],
        'email' => [
            'invalid_recipient_type' => 'Invalid recipient type selected.',
            'no_recipients_selected' => 'No recipients selected for the email.',
            'unknown_error' => 'An unknown error occurred while sending the email. Please try again later.',
            'will_be_sent' => 'The email will be sent shortly',
            'retry_empty_recipients' => 'Failed to retry sending the email, not failed recipients found.',
            'retry_success' => 'Email retry successful. :count recipient(s) will receive the email.',
        ],
    ],
];
