<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\LanguageCode;
use App\Enums\TransactionMetaType;
use App\Enums\WalletName;
use App\Models\ChurchWallet;
use App\Models\CurrentYear;
use App\Models\ExpenseType;
use App\Models\Member;
use App\Models\Missionary;
use App\Models\OfferingType;
use App\Models\Tag;
use Illuminate\Database\Seeder;

final class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed the tenants database.
     */
    public function run(): void
    {
        $currentYear = CurrentYear::create([
            'year' => date('Y'),
            'start_date' => now()->startOfYear(),
            'end_date' => now()->endOfYear(),
            'is_current' => true,
        ]);

        if (app()->environment('testing')) {
            return;
        }

        $this->call([
            Tenants\CategorySeeder::class,
            Tenants\UserSeeder::class,
        ]);

        $wallet = ChurchWallet::create([
            'name' => tenant('locale') === LanguageCode::ENGLISH->value ? 'Primary' : 'Principal',
            'description' => tenant('locale') === LanguageCode::ENGLISH->value ? 'This is the primary wallet' : 'Esta es la billetera principal',
            'slug' => WalletName::PRIMARY->value,
        ]);

        if (app()->environment(['local', 'staging'])) {
            $wallet->depositFloat('100.00', ['type' => TransactionMetaType::INITIAL->value, 'year' => $currentYear->id]);
            Member::factory(10)->create();
            Missionary::factory(10)->create();

            ExpenseType::factory(5)
                ->sequence(
                    ['name' => 'Water'],
                    ['name' => 'Loan Payment'],
                    ['name' => 'Internet'],
                    ['name' => 'Electricity'],
                    ['name' => 'Rent'],
                )
                ->create();

            OfferingType::factory(3)
                ->sequence(
                    ['name' => 'Regular'],
                    ['name' => 'Tithes'],
                    ['name' => 'Pro Temple'],
                )
                ->create();

            Tag::factory(9)
                ->skill()
                ->sequence(
                    ['name' => 'Gardening'],
                    ['name' => 'Mechanic'],
                    ['name' => 'Sales'],
                    ['name' => 'Cooking'],
                    ['name' => 'Air Conditioning'],
                    ['name' => 'Plumbing'],
                    ['name' => 'Electrician'],
                    ['name' => 'Carpentry'],
                    ['name' => 'Painting'],
                )
                ->create();
            Tag::factory(9)
                ->category()
                ->sequence(
                    ['name' => 'Missionary'],
                    ['name' => 'Secretary'],
                    ['name' => 'Director'],
                    ['name' => 'Pastor'],
                    ['name' => 'Deacon'],
                    ['name' => 'Treasurer'],
                    ['name' => 'Usher'],
                    ['name' => 'Worship Leader'],
                    ['name' => 'Youth Leader'],
                )
                ->create();

            $this->call([
                Tenants\VisitSeeder::class,
            ]);
        }

    }
}
