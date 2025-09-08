<?php

declare(strict_types=1);

use App\Enums\LanguageCode;
use App\Enums\WalletName;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('church_wallets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_routing_number')->nullable();
            $table->foreignId('check_layout_id')->nullable()->constrained('check_layouts');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('church_wallets')->insert([
            'name' => tenant('locale') === LanguageCode::ENGLISH->value ? 'Primary' : 'Principal',
            'description' => tenant('locale') === LanguageCode::ENGLISH->value ? 'This is the primary wallet' : 'Esta es la billetera principal',
            'slug' => WalletName::PRIMARY->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
};
