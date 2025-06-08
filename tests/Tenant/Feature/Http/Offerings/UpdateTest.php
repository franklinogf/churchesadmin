<?php

declare(strict_types=1);

use App\Enums\ModelMorphName;
use App\Enums\PaymentMethod;
use App\Enums\TenantPermission;
use App\Models\ChurchWallet;
use App\Models\Offering;
use App\Models\OfferingType;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    $offering = Offering::factory()->create();

    get(route('offerings.edit', $offering))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::OFFERINGS_MANAGE, TenantPermission::OFFERINGS_UPDATE);
    });

    it('can be rendered if authenticated', function (): void {
        $offering = Offering::factory()->create();

        get(route('offerings.edit', $offering))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('offerings/edit')
                ->has('paymentMethods')
                ->has('walletsOptions')
                ->has('membersOptions')
                ->has('offeringTypesOptions')
                ->has('missionariesOptions')
                ->has('offering')
                ->where('offering.id', $offering->id)
            );
    });

    it('can update an offering', function (): void {
        $wallet = ChurchWallet::factory()->create();
        $offeringType = OfferingType::factory()->create();
        $offering = Offering::factory()->create([
            'payment_method' => PaymentMethod::CASH,
        ]);

        $updateData = [
            'date' => '2025-06-07',
            'wallet_id' => $wallet->id,
            'payment_method' => PaymentMethod::CHECK->value,
            'offering_type' => [
                'id' => $offeringType->id,
                'model' => ModelMorphName::OFFERING_TYPE->value,
            ],
            'amount' => '150.00',
            'note' => 'Updated offering',
        ];

        $this->from(route('offerings.edit', $offering))
            ->put(route('offerings.update', $offering), $updateData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('offerings.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('offerings', [
            'id' => $offering->id,
            'date' => '2025-06-07',
            'payment_method' => PaymentMethod::CHECK->value,
        ]);
    });

    it('validates required fields on update', function (): void {
        $offering = Offering::factory()->create();

        $this->from(route('offerings.edit', $offering))
            ->put(route('offerings.update', $offering), [])
            ->assertSessionHasErrors(['date', 'wallet_id', 'payment_method', 'offering_type', 'amount']);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot access the edit offering form', function (): void {
        $offering = Offering::factory()->create();

        get(route('offerings.edit', $offering))
            ->assertStatus(403);
    });

    it('cannot update an offering', function (): void {
        $offering = Offering::factory()->create();
        $wallet = ChurchWallet::factory()->create();
        $offeringType = OfferingType::factory()->create();

        $updateData = [
            'date' => '2025-06-07',
            'wallet_id' => $wallet->id,
            'payment_method' => PaymentMethod::CHECK->value,
            'offering_type' => [
                'id' => $offeringType->id,
                'model' => OfferingType::class,
            ],
            'amount' => '150.00',
        ];

        $this->put(route('offerings.update', $offering), $updateData)
            ->assertStatus(403);
    });
});
