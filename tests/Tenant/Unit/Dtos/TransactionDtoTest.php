<?php

declare(strict_types=1);

use App\Dtos\TransactionDto;
use App\Dtos\TransactionMetaDto;
use App\Enums\TransactionMetaType;
use App\Models\CurrentYear;

describe('TransactionDto', function (): void {
    it('can be instantiated', function (): void {
        $amount = '100.00';
        $meta = new TransactionMetaDto(TransactionMetaType::CHECK);
        $confirmed = true;

        $dto = new TransactionDto(
            amount: $amount,
            meta: $meta,
            confirmed: $confirmed
        );

        expect($dto)->toBeInstanceOf(TransactionDto::class)
            ->and($dto->amount)->toBe($amount)
            ->and($dto->meta)->toBe($meta)
            ->and($dto->confirmed)->toBe($confirmed);
    });

    it('can be instantiated with default confirmed value', function (): void {
        $amount = '100.00';
        $meta = new TransactionMetaDto(TransactionMetaType::CHECK);

        $dto = new TransactionDto(
            amount: $amount,
            meta: $meta
        );

        expect($dto)->toBeInstanceOf(TransactionDto::class)
            ->and($dto->amount)->toBe($amount)
            ->and($dto->meta)->toBe($meta)
            ->and($dto->confirmed)->toBeTrue();
    });

    it('can be converted to array', function (): void {
        $amount = '100.00';
        $meta = new TransactionMetaDto(TransactionMetaType::OFFERING);
        $confirmed = false;

        $dto = new TransactionDto(
            amount: $amount,
            meta: $meta,
            confirmed: $confirmed
        );

        $array = $dto->toArray();

        expect($array)->toBe([
            'meta' => $meta->toArray(),
            'amount' => $amount,
            'confirmed' => $confirmed,
        ]);
    });

    it('can be serialized to JSON', function (): void {
        $amount = '100.00';
        $meta = new TransactionMetaDto(TransactionMetaType::EXPENSE);
        $confirmed = false;

        $dto = new TransactionDto(
            amount: $amount,
            meta: $meta,
            confirmed: $confirmed
        );

        $json = json_encode($dto);
        $decoded = json_decode($json, true);

        expect($decoded)->toBe([
            'meta' => [
                'type' => TransactionMetaType::EXPENSE->value,
                'year' => CurrentYear::current()->id,
            ],
            'amount' => $amount,
            'confirmed' => $confirmed,
        ]);
    });
});
