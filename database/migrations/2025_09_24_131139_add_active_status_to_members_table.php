<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->boolean('active')->default(true)->after('civil_status');
            $table->foreignId('deactivation_code_id')->nullable()->constrained('deactivation_codes')->nullOnDelete()->after('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['deactivation_code_id']);
            $table->dropColumn(['active', 'deactivation_code_id']);
        });
    }
};
