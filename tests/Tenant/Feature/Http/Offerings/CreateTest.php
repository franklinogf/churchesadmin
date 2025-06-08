<?php

declare(strict_types=1);

use App\Enums\ModelMorphName;
use App\Enums\PaymentMethod;
use App\Enums\TenantPermission;
use App\Models\ChurchWallet;
use App\Models\Member;
use App\Models\OfferingType;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    get(route('offerings.create'))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::OFFERINGS_MANAGE, TenantPermission::OFFERINGS_CREATE);
    });

    it('can be rendered if authenticated', function (): void {
        get(route('offerings.create'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('offerings/create')
                ->has('paymentMethods')
                ->has('walletsOptions')
                ->has('membersOptions')
                ->has('offeringTypesOptions')
                ->has('missionariesOptions')
            );
    });

    it('can store an offering', function (): void {
        $wallet = ChurchWallet::factory()->create();
        $member = Member::factory()->create();
        $offeringType = OfferingType::factory()->create();

        $offeringData = [
            'donor_id' => $member->id,
            'date' => '2025-06-07',
            'offerings' => [
                [
                    'wallet_id' => (string) $wallet->id,
                    'payment_method' => PaymentMethod::CASH->value,
                    'offering_type' => [
                        'id' => (string) $offeringType->id,
                        'model' => ModelMorphName::OFFERING_TYPE->value,
                    ],
                    'amount' => '100.00',
                    'note' => 'Test offering',
                ],
            ],
        ];

        $this->from(route('offerings.create'))
            ->post(route('offerings.store'), $offeringData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('offerings.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('offerings', [
            'donor_id' => $member->id,
            'date' => '2025-06-07',
            'payment_method' => PaymentMethod::CASH->value,
        ]);
    });

    it('validates required fields', function (): void {
        $this->from(route('offerings.create'))
            ->post(route('offerings.store'), [])
            ->assertSessionHasErrors(['date', 'offerings']);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot access the create offering form', function (): void {
        get(route('offerings.create'))
            ->assertStatus(403);
    });

    it('cannot store an offering', function (): void {
        $wallet = ChurchWallet::factory()->create();
        $offeringType = OfferingType::factory()->create();

        $offeringData = [
            'date' => '2025-06-07',
            'offerings' => [
                [
                    'wallet_id' => (string) $wallet->id,
                    'payment_method' => PaymentMethod::CASH->value,
                    'offering_type' => [
                        'id' => (string) $offeringType->id,
                        'model' => 'offering_type',
                    ],
                    'amount' => '100.00',
                ],
            ],
        ];

        $this->post(route('offerings.store'), $offeringData)
            ->assertStatus(403);
    });
});
