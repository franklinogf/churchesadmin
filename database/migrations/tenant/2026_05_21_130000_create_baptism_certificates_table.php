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
        Schema::create('baptism_certificates', function (Blueprint $table): void {
            $table->id();
            $table->string('book');
            $table->string('folio');
            $table->string('record_number');
            $table->string('baptized_name');
            $table->date('baptized_at')->nullable();
            $table->string('priest')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_origin_place')->nullable();
            $table->string('father_residence_place')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_origin_place')->nullable();
            $table->string('mother_residence_place')->nullable();
            $table->string('paternal_grandfather_name')->nullable();
            $table->string('paternal_grandmother_name')->nullable();
            $table->string('maternal_grandfather_name')->nullable();
            $table->string('maternal_grandmother_name')->nullable();
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
        Schema::dropIfExists('baptism_certificates');
    }
};
