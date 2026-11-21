<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 80);
            $table->date('event_date')->nullable();
            $table->string('budget', 40)->nullable();
            $table->string('theme', 24)->default('evergreen');
            $table->string('status', 16)->default('open');
            $table->char('invite_token_hash', 64)->unique();
            $table->string('creator_code_hash');
            $table->timestamps();
        });

        Schema::create('exchange_participants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('exchange_id')->constrained('exchanges')->cascadeOnDelete();
            $table->string('name', 60);
            $table->char('reveal_token_hash', 64)->nullable()->unique();
            $table->uuid('recipient_id')->nullable();
            $table->timestamps();

            $table->unique(['exchange_id', 'name']);
            $table->index(['exchange_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_participants');
        Schema::dropIfExists('exchanges');
    }
};
