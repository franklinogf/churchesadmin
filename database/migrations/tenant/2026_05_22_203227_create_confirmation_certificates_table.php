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
        Schema::create('confirmation_certificates', function (Blueprint $table): void {
            $table->id();
            $table->string('book');
            $table->string('folio');
            $table->string('record_number');
            $table->string('priest')->nullable();
            $table->string('confirmed_name');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('confirmed_by')->nullable();
            $table->date('confirmed_at')->nullable();
            $table->string('godfather_name')->nullable();
            $table->string('godmother_name')->nullable();
            $table->string('issued_place')->nullable();
            $table->date('issued_at')->nullable();
            $table->text('marginal_note')->nullable();
            $table->timestamps();

            $table->unique(['book', 'folio', 'record_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confirmation_certificates');
    }
};
