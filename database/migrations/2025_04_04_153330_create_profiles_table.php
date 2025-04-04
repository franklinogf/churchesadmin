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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('lastname');
            $table->date('dob');
            $table->tinyText('gender')->index();
            $table->string('civil_status')->index();
            $table->boolean('is_active')->index();
            $table->string('phone')->unique()->nullable();
            $table->string('language', 2);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
