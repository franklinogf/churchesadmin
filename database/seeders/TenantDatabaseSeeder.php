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
            ExpenseType::factory(5)->create();
            OfferingType::factory(5)->create();
            Tag::factory(3)->skill()->create();
            Tag::factory(3)->category()->create();
            $this->call([
                Tenants\VisitSeeder::class,
            ]);
        }

    }
}
