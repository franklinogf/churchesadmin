<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\WalletName;
use App\Models\Church;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $church = Church::create([
            'name' => 'Test Church',
        ]);
        $church->createDomain('test');
        $church->createWallet([
            'name' => [
                'en' => 'Primary Wallet',
                'es' => 'Billetera Principal',
            ],
            'description' => [
                'en' => 'This is the primary wallet',
                'es' => 'Esta es la billetera principal',
            ],
            'slug' => WalletName::PRIMARY->value,
        ]);
    }
}
