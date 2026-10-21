<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialLinkController;
use App\Http\Controllers\AppearanceController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PublicProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── Public landing page ──────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

// ── Breeze auth routes (login, register, password reset, etc.) ────────────
require __DIR__.'/auth.php';

// ── Authenticated & verified routes ──────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (bio, username, avatar)
    Route::get('/profile/edit',   [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',      [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',     [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Social Links (full CRUD + ordering + toggle)
    Route::resource('links', SocialLinkController::class);
    Route::patch('/links/{link}/toggle', [SocialLinkController::class, 'toggle'])->name('links.toggle');
    Route::post('/links/reorder',        [SocialLinkController::class, 'reorder'])->name('links.reorder');

    // Appearance / theme customisation
    Route::get('/appearance',   [AppearanceController::class, 'edit'])->name('appearance.edit');
    Route::patch('/appearance', [AppearanceController::class, 'update'])->name('appearance.update');

    // Analytics dashboard
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    // QR Code
    Route::get('/qrcode',          [QrCodeController::class, 'show'])->name('qrcode.show');
    Route::get('/qrcode/download', [QrCodeController::class, 'download'])->name('qrcode.download');

    // Account settings (email, password)
    Route::get('/settings',   [SettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');

});

// ── Public profile page & link-click tracking (Wildcard at bottom) ────────
Route::get('/{username}', [PublicProfileController::class, 'show'])->name('profile.show');
Route::get('/{username}/click/{link}', [PublicProfileController::class, 'click'])->name('profile.click');
