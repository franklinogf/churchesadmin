<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marriage_certificates', function (Blueprint $table): void {
            $table->id();
            $table->string('book');
            $table->string('folio');
            $table->string('record_number');
            $table->date('married_at')->nullable();
            $table->string('priest')->nullable();
            $table->string('groom_name');
            $table->string('groom_age')->nullable();
            $table->string('groom_birthplace')->nullable();
            $table->string('groom_residence')->nullable();
            $table->string('groom_father_name')->nullable();
            $table->string('groom_mother_name')->nullable();
            $table->string('bride_name');
            $table->string('bride_age')->nullable();
            $table->string('bride_birthplace')->nullable();
            $table->string('bride_residence')->nullable();
            $table->string('bride_father_name')->nullable();
            $table->string('bride_mother_name')->nullable();
            $table->string('witness1_name')->nullable();
            $table->string('witness2_name')->nullable();
            $table->date('issued_at')->nullable();
            $table->text('marginal_note')->nullable();
            $table->timestamps();

            $table->unique(['book', 'folio', 'record_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marriage_certificates');
    }
};
