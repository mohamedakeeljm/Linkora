<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsEvent extends Model
{
    // This model only has created_at (no updated_at)
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'type',
        'link_id',
        'ip_hash',
        'referer',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ─── Relationships ───────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function link(): BelongsTo
    {
        return $this->belongsTo(SocialLink::class, 'link_id');
    }

    // ─── Static Helpers ──────────────────────────────────────────────────────

    /**
     * Hash an IP address for storage.
     * We use a keyed HMAC so the result can't be reversed without APP_KEY.
     */
    public static function hashIp(?string $ip): ?string
    {
        if (! $ip) {
            return null;
        }

        return hash_hmac('sha256', $ip, config('app.key'));
    }

    /** Record a profile page view */
    public static function recordView(User $user, string $ip, ?string $referer, ?string $ua): void
    {
        static::create([
            'user_id'    => $user->id,
            'type'       => 'view',
            'ip_hash'    => static::hashIp($ip),
            'referer'    => $referer ? substr($referer, 0, 2048) : null,
            'user_agent' => $ua ? substr($ua, 0, 512) : null,
        ]);
    }

    /** Record a link click */
    public static function recordClick(User $user, SocialLink $link, string $ip, ?string $ua): void
    {
        static::create([
            'user_id'    => $user->id,
            'type'       => 'click',
            'link_id'    => $link->id,
            'ip_hash'    => static::hashIp($ip),
            'user_agent' => $ua ? substr($ua, 0, 512) : null,
        ]);
    }
}
