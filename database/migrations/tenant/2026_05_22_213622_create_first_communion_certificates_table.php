<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('first_communion_certificates', function (Blueprint $table): void {
            $table->id();
            $table->string('priest')->nullable();
            $table->string('communicant_name');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->date('communion_at')->nullable();
            $table->date('issued_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('first_communion_certificates');
    }
};
