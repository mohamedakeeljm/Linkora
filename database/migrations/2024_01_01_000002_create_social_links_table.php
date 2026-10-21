<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * social_links table
 *
 * Stores the individual link buttons that appear on a user's public profile.
 *
 * platform    – machine key identifying the platform (instagram, github, etc.)
 * label       – human-readable button label (e.g. "Follow me on Instagram")
 * url         – the actual destination URL
 * sort_order  – integer used to drag-and-drop reorder links (ASC)
 * is_active   – when false the link is hidden from the public profile
 * click_count – running total of clicks tracked for analytics
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('platform', 50);
            $table->string('label', 100);
            $table->string('url', 2048);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('click_count')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'sort_order']);
            $table->index(['user_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};
