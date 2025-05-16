<?php

declare(strict_types=1);

namespace App\Enums;

enum CheckLayoutField: string
{
    case PAYEE = 'payee';
    case AMOUNT = 'amount';
    case DATE = 'date';
    case MEMO = 'memo';
    case AMOUNT_IN_WORDS = 'amount_in_words';

    public static function initialLayout(): array
    {
        return collect(self::cases())
            ->mapWithKeys(function (self $field, $index) {
                return [
                    $field->value => [
                        'position' => [
                            'x' => 0,
                            'y' => $index * 20,
                        ],
                    ],
                ];
            })->toArray();
    }
}
