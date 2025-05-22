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
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('body');
            $table->foreignUuid('sender_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('recipient_type');
            $table->string('reply_to')->nullable();
            $table->string('status');
            $table->timestamp('sent_at')->nullable();
            $table->string('error_message')->nullable();
            $table->timestamps();
        });

        Schema::create('emailables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_id')->constrained('emails')->cascadeOnDelete();
            $table->morphs('recipient');
            $table->string('status');
            $table->timestamp('sent_at')->nullable();
            $table->string('error_message')->nullable();
            $table->timestamps();
        });
    }
};
