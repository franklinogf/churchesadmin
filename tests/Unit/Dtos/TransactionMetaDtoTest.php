<?php

declare(strict_types=1);

use App\Dtos\TransactionMetaDto;
use App\Enums\TransactionMetaType;

describe('TransactionMetaDto', function () {
    it('can be instantiated', function () {
        $type = TransactionMetaType::CHECK;
        $dto = new TransactionMetaDto($type);

        expect($dto)->toBeInstanceOf(TransactionMetaDto::class)
            ->and($dto->type)->toBe($type);
    });

    it('can be converted to array', function () {
        $type = TransactionMetaType::OFFERING;
        $dto = new TransactionMetaDto($type);

        $array = $dto->toArray();

        expect($array)->toBe([
            'type' => $type->value,
        ]);
    });

    it('can be serialized to JSON', function () {
        $type = TransactionMetaType::EXPENSE;
        $dto = new TransactionMetaDto($type);

        $json = json_encode($dto);
        $decoded = json_decode($json, true);

        expect($decoded)->toBe([
            'type' => $type->value,
        ]);
    });
});
