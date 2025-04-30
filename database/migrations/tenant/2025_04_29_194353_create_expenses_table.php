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

        Schema::create('expenses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('transaction_id');
            $table->foreignId('expense_type_id')->constrained('expense_types', 'id');
            $table->foreignId('member_id')->nullable()->constrained('members', 'id');
            $table->timestamp('date');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
