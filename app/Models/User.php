<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'bio',
        'website',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ─── Relationships ───────────────────────────────────────────────────────

    /** All social links belonging to this user */
    public function socialLinks(): HasMany
    {
        return $this->hasMany(SocialLink::class)->orderBy('sort_order');
    }

    /** Only active links (used on public profile) */
    public function activeLinks(): HasMany
    {
        return $this->hasMany(SocialLink::class)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /** Profile customisation settings (one-to-one) */
    public function profileSettings(): HasOne
    {
        return $this->hasOne(ProfileSetting::class);
    }

    /** Raw analytics events */
    public function analyticsEvents(): HasMany
    {
        return $this->hasMany(AnalyticsEvent::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /** Return the public profile URL for this user */
    public function profileUrl(): string
    {
        return route('profile.show', $this->username);
    }

    /** Return avatar URL or a generated placeholder */
    public function avatarUrl(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // UI-Avatars fallback — generates a coloured initial avatar
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&background=7c3aed&color=fff&size=200&bold=true";
    }

    /** Ensure profile settings always exist (create with defaults if missing) */
    public function getOrCreateSettings(): ProfileSetting
    {
        return $this->profileSettings ?? $this->profileSettings()->create([]);
    }

    /** Total profile views */
    public function totalViews(): int
    {
        return $this->analyticsEvents()->where('type', 'view')->count();
    }

    /** Total link clicks */
    public function totalClicks(): int
    {
        return $this->analyticsEvents()->where('type', 'click')->count();
    }
}
