<?php

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
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('service', 50)->unique()->comment('Service name: gemini, openai, etc');
            $table->text('encrypted_key')->comment('Laravel encrypted API key');
            $table->boolean('is_active')->default(true)->comment('Is this key currently active');
            $table->string('environment', 20)->default('production')->comment('production, staging, development');
            $table->timestamp('last_used_at')->nullable()->comment('Last time this key was used');
            $table->integer('usage_count')->default(0)->comment('How many times this key has been used');
            $table->string('updated_by', 100)->nullable()->comment('Who last updated this key');
            $table->text('notes')->nullable()->comment('Additional notes about this key');
            $table->timestamps();

            // Indexes
            $table->index('service');
            $table->index('is_active');
            $table->index('environment');
        });

        // Audit table untuk tracking key changes
        Schema::create('api_key_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_key_id')->nullable()->constrained('api_keys')->onDelete('set null');
            $table->string('service', 50);
            $table->string('action', 50)->comment('created, updated, rotated, deleted, accessed');
            $table->string('performed_by', 100)->nullable()->comment('User who performed the action');
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->json('metadata')->nullable()->comment('Additional context');
            $table->timestamp('performed_at');

            // Indexes
            $table->index('service');
            $table->index('action');
            $table->index('performed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_key_audits');
        Schema::dropIfExists('api_keys');
    }
};