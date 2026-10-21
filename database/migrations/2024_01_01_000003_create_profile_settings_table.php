<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * profile_settings table
 *
 * One row per user. Stores all visual customisation choices.
 *
 * theme        – preset theme name (default, ocean, sunset, forest, midnight)
 * bg_color     – custom hex background colour, overrides theme when set
 * accent_color – custom hex accent / button colour
 * button_style – pill | rounded | sharp | outline | glass
 * layout       – centered | left | compact
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('theme', 30)->default('default');
            $table->string('bg_color', 7)->nullable();
            $table->string('accent_color', 7)->nullable();
            $table->string('button_style', 20)->default('pill');
            $table->string('layout', 20)->default('centered');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_settings');
    }
};
