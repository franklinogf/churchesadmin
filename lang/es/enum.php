<?php

declare(strict_types=1);

return [
    'follow_up_type' => [
        'call' => 'Llamada',
        'email' => 'Correo Electrónico',
        'in_person' => 'En Persona',
        'letter' => 'Carta',
    ],
    'email_status' => [
        'pending' => 'Pendiente',
        'sent' => 'Enviado',
        'failed' => 'Fallido',
        'sending' => 'Enviando',
    ],
    'model_morph_name' => [
        'member' => 'Miembro',
        'missionary' => 'Misionero',
        'user' => 'Usuario',
        'church' => 'Iglesia',
        'church_wallet' => 'Billetera',
        'offering_type' => 'Tipo de Ofrenda',
        'check_layout' => 'Diseño de Cheque',
        'email' => 'Email',
        'visit' => 'Visita',
    ],
    'wallet_name' => [
        'primary' => 'Principal',
    ],
    'payment_method' => [
        'cash' => 'Efectivo',
        'check' => 'Cheque',
    ],
    'transaction_type' => [
        'deposit' => 'Depósito',
        'withdraw' => 'Retiro',
        'previous_balance' => 'Saldo Anterior',
    ],
    'transaction_meta_type' => [
        'initial' => 'Inicial',
        'check' => 'Cheque',
        'offering' => 'Ofrenda',
        'expense' => 'Gasto',
    ],
    'civil_status' => [
        'single' => 'Soltero/a',
        'married' => 'Casado/a',
        'divorced' => 'Divorciado/a',
        'widowed' => 'Viudo/a',
        'separated' => 'Separado/a',
    ],
    'gender' => [
        'male' => 'Masculino',
        'female' => 'Femenino',
    ],
    'language_code' => [
        'en' => 'Inglés',
        'es' => 'Español',
    ],
    'check_type' => [
        'payment' => 'Pago',
        'refund' => 'Reembolso',
    ],
    'offering_frequency' => [
        'weekly' => 'Cada semana',
        'bi_weekly' => 'Cada dos semanas',
        'monthly' => 'Cada mes',
        'bi_monthly' => 'Cada dos meses',
        'quarterly' => 'Cada tres meses',
        'semi_annually' => 'Cada seis meses',
        'annually' => 'Cada año',
        'one_time' => 'Solo una vez',
    ],
    'tag_type' => [
        'skill' => 'Habilidad',
        'category' => 'Categoría',
    ],
    'tenant_role' => [
        'super_admin' => 'Súper Administrador',
        'admin' => 'Administrador',
        'secretary' => 'Secretario/a',
        'no_role' => 'Sin Rol',
    ],
    'tenant_permission' => [
        'regular_tags' => [
            'update' => 'Actualizar Etiquetas Regulares',
            'delete' => 'Eliminar Etiquetas Regulares',
            'create' => 'Crear Etiquetas Regulares',
        ],
        'users' => [
            'manage' => 'Gestionar Usuarios',
            'create' => 'Crear Usuarios',
            'update' => 'Actualizar Usuarios',
            'delete' => 'Eliminar Usuarios',
        ],
        'skills' => [
            'manage' => 'Gestionar Habilidades',
            'create' => 'Crear Habilidades',
            'update' => 'Actualizar Habilidades',
            'delete' => 'Eliminar Habilidades',
        ],
        'categories' => [
            'manage' => 'Gestionar Categorías',
            'create' => 'Crear Categorías',
            'update' => 'Actualizar Categorías',
            'delete' => 'Eliminar Categorías',
        ],
        'members' => [
            'manage' => 'Gestionar Miembros',
            'create' => 'Crear Miembros',
            'update' => 'Actualizar Miembros',
            'delete' => 'Eliminar Miembros',
            'force_delete' => 'Forzar Eliminación de Miembros',
            'restore' => 'Restaurar Miembros',
            'deactivate' => 'Desactivar Miembros',
            'activate' => 'Activar Miembros',
        ],
        'missionaries' => [
            'manage' => 'Gestionar Misioneros',
            'create' => 'Crear Misioneros',
            'update' => 'Actualizar Misioneros',
            'delete' => 'Eliminar Misioneros',
            'force_delete' => 'Forzar Eliminación de Misioneros',
            'restore' => 'Restaurar Misioneros',
        ],
        'offerings' => [
            'manage' => 'Gestionar Ofrendas',
            'create' => 'Crear Ofrendas',
            'update' => 'Actualizar Ofrendas',
            'delete' => 'Eliminar Ofrendas',
        ],
        'offering_types' => [
            'manage' => 'Gestionar Tipos de Ofrenda',
            'create' => 'Crear Tipos de Ofrenda',
            'update' => 'Actualizar Tipos de Ofrenda',
            'delete' => 'Eliminar Tipos de Ofrenda',
        ],
        'expense_types' => [
            'manage' => 'Gestionar Tipos de Gastos',
            'create' => 'Crear Tipos de Gastos',
            'update' => 'Actualizar Tipos de Gastos',
            'delete' => 'Eliminar Tipos de Gastos',
        ],
        'wallets' => [
            'manage' => 'Gestionar Billeteras',
            'create' => 'Crear Billetera',
            'update' => 'Actualizar Billetera',
            'delete' => 'Eliminar Billetera',
            'check_layout' => [
                'update' => 'Actualizar Diseño de Billetera',
            ],
        ],
        'check_layouts' => [
            'manage' => 'Gestionar Diseños de Cheques',
            'create' => 'Crear Diseño de Cheques',
            'update' => 'Actualizar Diseño de Cheques',
            'delete' => 'Eliminar Diseño de Cheques',
        ],
        'checks' => [
            'manage' => 'Gestionar Cheques',
            'create' => 'Crear Cheques',
            'update' => 'Actualizar Cheques',
            'delete' => 'Eliminar Cheques',
            'confirm' => 'Confirmar Cheques',
            'print' => 'Imprimir Cheques',
        ],
        'emails' => [
            'manage' => 'Gestionar Correos Electrónicos',
            'create' => 'Crear Correos Electrónicos',
            'update' => 'Actualizar Correos Electrónicos',
            'delete' => 'Eliminar Correos Electrónicos',
            'send' => 'Enviar Correos Electrónicos',
            'send_to' => [
                'members' => 'Enviar Correos Electrónicos a Miembros',
                'missionaries' => 'Enviar Correos Electrónicos a Misioneros',
            ],
        ],
        'visits' => [
            'manage' => 'Gestionar Visitas',
            'create' => 'Crear Visitas',
            'update' => 'Actualizar Visitas',
            'delete' => 'Eliminar Visitas',
            'force_delete' => 'Forzar Eliminación de Visitas',
            'restore' => 'Restaurar Visitas',
        ],

    ],
];
