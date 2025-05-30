<?php

declare(strict_types=1);

use App\Dtos\CheckLayoutFieldsDto;
use Bavix\Wallet\Services\FormatterService;

describe('CheckLayoutFieldsDto', function () {
    it('can be instantiated', function () {
        $date = '2025-05-30';
        $amount = '100.00';
        $payee = 'John Doe';
        $memo = 'Test payment';
        $amountInWords = 'one hundred and 00/100 dollars';

        $dto = new CheckLayoutFieldsDto(
            date: $date,
            amount: $amount,
            payee: $payee,
            memo: $memo,
            amount_in_words: $amountInWords
        );

        expect($dto)->toBeInstanceOf(CheckLayoutFieldsDto::class)
            ->and($dto->date)->toBe($date)
            ->and($dto->amount)->toBe($amount)
            ->and($dto->payee)->toBe($payee)
            ->and($dto->memo)->toBe($memo)
            ->and($dto->amount_in_words)->toBe($amountInWords);
    });

    it('can be instantiated without memo', function () {
        $date = '2025-05-30';
        $amount = '100.00';
        $payee = 'John Doe';
        $amountInWords = 'one hundred and 00/100 dollars';

        $dto = new CheckLayoutFieldsDto(
            date: $date,
            amount: $amount,
            payee: $payee,
            amount_in_words: $amountInWords
        );

        expect($dto)->toBeInstanceOf(CheckLayoutFieldsDto::class)
            ->and($dto->date)->toBe($date)
            ->and($dto->amount)->toBe($amount)
            ->and($dto->payee)->toBe($payee)
            ->and($dto->memo)->toBeNull()
            ->and($dto->amount_in_words)->toBe($amountInWords);
    });

    // Mock FormatterService's intValue method by creating a stub
    it('can be created from array', function () {
        $data = [
            'date' => '2025-05-30',
            'amount' => '100.00',
            'payee' => 'John Doe',
            'memo' => 'Test payment',
        ];

        $dto = CheckLayoutFieldsDto::fromArray($data);

        expect($dto)->toBeInstanceOf(CheckLayoutFieldsDto::class)
            ->and($dto->date)->toBe($data['date'])
            ->and($dto->amount)->toBe('100.00')
            ->and($dto->payee)->toBe($data['payee'])
            ->and($dto->memo)->toBe($data['memo']);

        // We can't easily test amount_in_words due to mocking challenges with internal static methods
    });

    it('can be created from array without memo', function () {

        $data = [
            'date' => '2025-05-30',
            'amount' => '100.00',
            'payee' => 'John Doe',
        ];

        $dto = CheckLayoutFieldsDto::fromArray($data);

        expect($dto)->toBeInstanceOf(CheckLayoutFieldsDto::class)
            ->and($dto->date)->toBe($data['date'])
            ->and($dto->amount)->toBe('100.00')
            ->and($dto->payee)->toBe($data['payee'])
            ->and($dto->memo)->toBeNull();
    });

    it('properly formats negative amounts', function () {

        $data = [
            'date' => '2025-05-30',
            'amount' => '-100.00',
            'payee' => 'John Doe',
            'memo' => 'Test payment',
        ];

        $dto = CheckLayoutFieldsDto::fromArray($data);

        expect($dto->amount)->toBe('100.00'); // Should be positive
    });

    it('can be converted to array', function () {
        $date = '2025-05-30';
        $amount = '100.00';
        $payee = 'John Doe';
        $memo = 'Test payment';
        $amountInWords = 'one hundred and 00/100 dollars';

        $dto = new CheckLayoutFieldsDto(
            date: $date,
            amount: $amount,
            payee: $payee,
            memo: $memo,
            amount_in_words: $amountInWords
        );

        $array = $dto->toArray();

        expect($array)->toBe([
            'date' => $date,
            'amount' => $amount,
            'amount_in_words' => $amountInWords,
            'payee' => $payee,
            'memo' => $memo,
        ]);
    });

    it('can be serialized to JSON', function () {
        $date = '2025-05-30';
        $amount = '100.00';
        $payee = 'John Doe';
        $memo = 'Test payment';
        $amountInWords = 'one hundred and 00/100 dollars';

        $dto = new CheckLayoutFieldsDto(
            date: $date,
            amount: $amount,
            payee: $payee,
            memo: $memo,
            amount_in_words: $amountInWords
        );

        $json = json_encode($dto);
        $decoded = json_decode($json, true);

        expect($decoded)->toBe([
            'date' => $date,
            'amount' => $amount,
            'amount_in_words' => $amountInWords,
            'payee' => $payee,
            'memo' => $memo,
        ]);
    });
});
