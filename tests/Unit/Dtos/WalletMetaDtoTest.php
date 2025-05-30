<?php

declare(strict_types=1);

use App\Dtos\WalletMetaDto;

describe('WalletMetaDto', function (): void {
    it('can be instantiated', function (): void {
        $bankName = 'Test Bank';
        $bankRoutingNumber = '123456789';
        $bankAccountNumber = '987654321';

        $dto = new WalletMetaDto(
            bankName: $bankName,
            bankRoutingNumber: $bankRoutingNumber,
            bankAccountNumber: $bankAccountNumber
        );

        expect($dto)->toBeInstanceOf(WalletMetaDto::class)
            ->and($dto->bankName)->toBe($bankName)
            ->and($dto->bankRoutingNumber)->toBe($bankRoutingNumber)
            ->and($dto->bankAccountNumber)->toBe($bankAccountNumber);
    });

    it('can be converted to array', function (): void {
        $bankName = 'Test Bank';
        $bankRoutingNumber = '123456789';
        $bankAccountNumber = '987654321';

        $dto = new WalletMetaDto(
            bankName: $bankName,
            bankRoutingNumber: $bankRoutingNumber,
            bankAccountNumber: $bankAccountNumber
        );

        $array = $dto->toArray();

        expect($array)->toBe([
            'bank_name' => $bankName,
            'bank_routing_number' => $bankRoutingNumber,
            'bank_account_number' => $bankAccountNumber,
        ]);
    });

    it('can be serialized to JSON', function (): void {
        $bankName = 'Test Bank';
        $bankRoutingNumber = '123456789';
        $bankAccountNumber = '987654321';

        $dto = new WalletMetaDto(
            bankName: $bankName,
            bankRoutingNumber: $bankRoutingNumber,
            bankAccountNumber: $bankAccountNumber
        );

        $json = json_encode($dto);
        $decoded = json_decode($json, true);

        expect($decoded)->toBe([
            'bank_name' => $bankName,
            'bank_routing_number' => $bankRoutingNumber,
            'bank_account_number' => $bankAccountNumber,
        ]);
    });
});
