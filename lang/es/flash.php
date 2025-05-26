<?php

declare(strict_types=1);

return [
    'success' => 'Éxito',
    'error' => 'Error',
    'warning' => 'Advertencia',
    'info' => 'Información',
    'notice' => 'Aviso',
    'alert' => 'Alerta',
    'message' => [
        'created' => ':model creado correctamente.',
        'updated' => ':model actualizado correctamente.',
        'deleted' => ':model eliminado correctamente.',
        'restored' => ':model restaurado correctamente.',
        'archived' => ':model archivado correctamente.',
        'unarchived' => ':model desarchivado correctamente.',
        'activated' => ':model activado correctamente.',
        'deactivated' => ':model desactivado correctamente.',
        'imported' => ':model importado correctamente.',
        'exported' => ':model exportado correctamente.',
        'synced' => ':model sincronizado correctamente.',
        'wallet' => [
            'insufficient_funds' => 'Fondos insuficientes en :wallet.',
            'empty_balance' => 'El saldo de :wallet está vacío.',
            'not_found' => 'Billetera no encontrada o ha sido eliminada.',
            'invalid_amount' => 'Cantidad inválida.',
            'transaction_failed' => 'Transacción fallida.',
            'already_confirmed' => 'La transacción ya ha sido confirmada.',
        ],
        'check' => [
            'number_generated' => 'Números de cheques generados correctamente.',
            'number_exists' => 'El siguiente número de cheque ya existe: :numbers|Los siguientes números de cheque ya existen: :numbers',
            'confirmed' => 'Cheques confirmados correctamente.',
        ],
        'email' => [
            'invalid_recipient_type' => 'Tipo de destinatario inválido seleccionado.',
            'no_recipients_selected' => 'No se seleccionaron destinatarios para el correo electrónico.',
            'unknown_error' => 'Ocurrió un error desconocido al enviar el correo electrónico. Por favor, inténtelo de nuevo más tarde.',
            'will_be_sent' => 'El correo electrónico se enviará pronto.',
        ],
    ],
];
