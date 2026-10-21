<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * analytics_events table
 *
 * Immutable log of every profile view and link click.
 * Never stores raw IPs — only a one-way SHA-256 hash for
 * approximate unique-visitor counting without privacy exposure.
 *
 * type      – 'view' (profile page loaded) | 'click' (a link was clicked)
 * link_id   – null for views, foreign-key to social_links for clicks
 * ip_hash   – sha256(ip + salt) — cannot be reversed to the real IP
 * referer   – where the visitor came from (null when direct / private)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['view', 'click']);
            $table->foreignId('link_id')->nullable()->constrained('social_links')->nullOnDelete();
            $table->string('ip_hash', 64)->nullable();
            $table->string('referer', 2048)->nullable();
            $table->string('user_agent', 512)->nullable();
            // Only created_at needed — events are never updated
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'created_at']);
            $table->index('link_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
