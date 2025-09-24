<?php

declare(strict_types=1);

use App\Enums\CheckType;
use App\Models\Check;
use App\Models\ExpenseType;
use App\Models\Member;
use Bavix\Wallet\Models\Transaction;
use Carbon\CarbonImmutable;
use Pest\Expectation;

test('to array', function (): void {
    $check = Check::factory()->create()->fresh();

    expect(array_keys($check->toArray()))->toBe([
        'id',
        'transaction_id',
        'member_id',
        'expense_type_id',
        'check_number',
        'date',
        'type',
        'note',
        'current_year_id',
        'created_at',
        'updated_at',
        'transaction',
    ]);
});

test('casts are applied correctly', function (): void {
    $check = Check::factory()->create()->fresh();

    expect($check->type)->toBeInstanceOf(CheckType::class);
    expect($check->date)->toBeInstanceOf(CarbonImmutable::class);
});

it('belongs to a transaction', function (): void {
    $check = Check::factory()->create()->fresh();

    expect($check->transaction)->toBeInstanceOf(Transaction::class);
    expect($check->transaction->id)->toBe($check->transaction_id);
});

it('belongs to a member', function (): void {
    $check = Check::factory()->create()->fresh();

    expect($check->member)->toBeInstanceOf(Member::class);
    expect($check->member->id)->toBe($check->member_id);
});

it('belongs to an expense type', function (): void {
    $check = Check::factory()->create()->fresh();

    expect($check->expenseType)->toBeInstanceOf(ExpenseType::class);
    expect($check->expenseType->id)->toBe($check->expense_type_id);
});

it('can determine if it is confirmed', function (): void {
    $confirmedCheck = Check::factory()->confirmed()->create();
    $unconfirmedCheck = Check::factory()->unconfirmed()->create();

    expect($confirmedCheck->isConfirmed())->toBeTrue();
    expect($unconfirmedCheck->isConfirmed())->toBeFalse();
});

it('can scope to confirmed checks', function (): void {
    Check::factory()->confirmed()->count(3)->create();
    Check::factory()->unconfirmed()->count(2)->create();

    $confirmedChecks = Check::confirmed()->get();

    expect($confirmedChecks)->toHaveCount(3);
    $confirmedChecks->each(fn (Check $check): Expectation => expect($check->isConfirmed())->toBeTrue());
});

it('can scope to unconfirmed checks', function (): void {
    Check::factory()->confirmed()->count(3)->create();
    Check::factory()->unconfirmed()->count(2)->create();

    $unconfirmedChecks = Check::unconfirmed()->get();

    expect($unconfirmedChecks)->toHaveCount(2);
    $unconfirmedChecks->each(fn (Check $check): Expectation => expect($check->isConfirmed())->toBeFalse());
});

it('has fields attribute with correct data', function (): void {
    $check = Check::factory()->create()->fresh();

    expect($check->fields)->toHaveProperties([
        'date',
        'amount',
        'payee',
        'memo',
    ]);

    expect($check->fields->date)->toBe($check->date->format('Y-m-d'));
    expect($check->fields->amount)->toBe(number_format(abs((float) $check->transaction->amountFloat), 2));
    expect($check->fields->payee)->toBe("{$check->member->name} {$check->member->last_name}");
    expect($check->fields->memo)->toBe($check->note);
});
