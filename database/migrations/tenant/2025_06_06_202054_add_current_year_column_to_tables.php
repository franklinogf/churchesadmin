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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('current_year_id')->after('timezone_country')->constrained('current_years');
        });

        Schema::table('offerings', function (Blueprint $table) {
            $table->foreignId('current_year_id')->after('note')->constrained('current_years');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('current_year_id')->after('note')->constrained('current_years');
        });

        Schema::table('checks', function (Blueprint $table) {
            $table->foreignId('current_year_id')->after('note')->constrained('current_years');
        });
    }
};
