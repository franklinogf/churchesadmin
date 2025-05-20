<?php

declare(strict_types=1);

namespace App\Dtos;

use Bavix\Wallet\Services\FormatterService;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use NumberToWords\NumberToWords;

/**
 * @implements Arrayable<string,string|null>
 */
final readonly class CheckLayoutFieldsDto implements JsonSerializable, Arrayable
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $date,
        public string $amount,
        public string $payee,
        public ?string $memo = null,
        public string $amount_in_words = '',
    ) {
        //
    }

    /**
     * @param  array{date:string,amount:string,payee:string,memo?:string|null}  $data
     * @return CheckLayoutFieldsDto
     */
    public static function fromArray(array $data): static
    {
        $amount = number_format(abs((float) $data['amount']), 2);

        return new self(
            date: $data['date'],
            amount: $amount,
            payee: $data['payee'],
            memo: $data['memo'] ?? null,
            amount_in_words: self::getAmountInWords($amount)
        );
    }

    /**
     * Get the instance as an array.
     *
     * @return array{date:string,amount:string,amount_in_words:string,payee:string,memo:string|null}
     */
    public function toArray(): array
    {

        return [
            'date' => $this->date,
            'amount' => $this->amount,
            'amount_in_words' => $this->amount_in_words,
            'payee' => $this->payee,
            'memo' => $this->memo,
        ];
    }

    /**
     * Specify the data which should be serialized to JSON.
     *
     * @return array{date:string,amount:string,amount_in_words:string,payee:string,memo:string|null}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    private static function getAmountInWords(string $amount): string
    {
        return NumberToWords::transformCurrency('en', (int) app(FormatterService::class)->intValue($amount, 2), 'USD');
    }
}
