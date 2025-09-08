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
        Schema::create('checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id');
            $table->foreignId('member_id')->constrained('members');
            $table->foreignId('expense_type_id')->constrained('expense_types');
            $table->string('check_number')->nullable();
            $table->date('date');
            $table->string('type');
            $table->string('note')->nullable();
            $table->timestamps();
        });

        Schema::create('check_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('width');
            $table->integer('height');
            $table->json('fields')->nullable();
            $table->timestamps();
        });

    }
};
