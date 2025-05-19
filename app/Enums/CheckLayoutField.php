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

    /**
     * The initial layout of the check fields.
     *
     * @return array<string,array{position:array{x:int,y:int}}>
     */
    public static function initialLayout(): array
    {
        /**
         * @var array<string,array{position:array{x:int,y:int}}> $initialLayout
         */
        $initialLayout = collect(self::cases())
            ->mapWithKeys(fn (self $field, int $index): array => [
                $field->value => [
                    'position' => [
                        'x' => 0,
                        'y' => $index * 20,
                    ],
                ],
            ])->toArray();

        return $initialLayout;
    }
}
