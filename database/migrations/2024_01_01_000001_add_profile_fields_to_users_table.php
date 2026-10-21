<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add Linkora-specific profile fields to the default users table.
 *
 * username   – unique handle used in the public URL (linkora.com/username)
 * avatar     – path to uploaded profile picture stored in storage/app/public
 * bio        – short description shown on the public profile
 * website    – optional personal website link
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 30)->unique()->nullable()->after('name');
            $table->string('avatar')->nullable()->after('username');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('website')->nullable()->after('bio');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'avatar', 'bio', 'website']);
        });
    }
};
