<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'platform',
        'label',
        'url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'sort_order'  => 'integer',
        'click_count' => 'integer',
    ];

    // ─── Relationships ───────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function analyticsEvents()
    {
        return $this->hasMany(AnalyticsEvent::class, 'link_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Map of supported platforms with icon, display name, color,
     * URL prefix / scheme, placeholder hint, and input type.
     */
    public static function supportedPlatforms(): array
    {
        return [
            'instagram'  => [
                'icon'        => 'fab fa-instagram',
                'name'        => 'Instagram',
                'color'       => '#E1306C',
                'prefix'      => 'https://instagram.com/',
                'placeholder' => 'username',
                'hint'        => 'Enter username (e.g. johndoe) or full URL',
                'type'        => 'text',
            ],
            'facebook'   => [
                'icon'        => 'fab fa-facebook',
                'name'        => 'Facebook',
                'color'       => '#1877F2',
                'prefix'      => 'https://facebook.com/',
                'placeholder' => 'username or page ID',
                'hint'        => 'Enter profile/page username or full URL',
                'type'        => 'text',
            ],
            'tiktok'     => [
                'icon'        => 'fab fa-tiktok',
                'name'        => 'TikTok',
                'color'       => '#010101',
                'prefix'      => 'https://tiktok.com/@',
                'placeholder' => 'username',
                'hint'        => 'Enter TikTok username without @ or full URL',
                'type'        => 'text',
            ],
            'youtube'    => [
                'icon'        => 'fab fa-youtube',
                'name'        => 'YouTube',
                'color'       => '#FF0000',
                'prefix'      => 'https://youtube.com/@',
                'placeholder' => 'channel or @handle',
                'hint'        => 'Enter @handle or full channel URL',
                'type'        => 'text',
            ],
            'linkedin'   => [
                'icon'        => 'fab fa-linkedin',
                'name'        => 'LinkedIn',
                'color'       => '#0A66C2',
                'prefix'      => 'https://linkedin.com/in/',
                'placeholder' => 'username',
                'hint'        => 'Enter LinkedIn profile handle or full URL',
                'type'        => 'text',
            ],
            'github'     => [
                'icon'        => 'fab fa-github',
                'name'        => 'GitHub',
                'color'       => '#f0f6fc',
                'prefix'      => 'https://github.com/',
                'placeholder' => 'username',
                'hint'        => 'Enter GitHub username or full URL',
                'type'        => 'text',
            ],
            'twitter'    => [
                'icon'        => 'fab fa-x-twitter',
                'name'        => 'X / Twitter',
                'color'       => '#e2e8f0',
                'prefix'      => 'https://x.com/',
                'placeholder' => 'username',
                'hint'        => 'Enter username without @ or full URL',
                'type'        => 'text',
            ],
            'whatsapp'   => [
                'icon'        => 'fab fa-whatsapp',
                'name'        => 'WhatsApp',
                'color'       => '#25D366',
                'prefix'      => 'https://wa.me/',
                'placeholder' => '919876543210',
                'hint'        => 'Enter phone number with country code (e.g. 919876543210)',
                'type'        => 'tel',
            ],
            'snapchat'   => [
                'icon'        => 'fab fa-snapchat',
                'name'        => 'Snapchat',
                'color'       => '#FFFC00',
                'prefix'      => 'https://snapchat.com/add/',
                'placeholder' => 'username',
                'hint'        => 'Enter Snapchat username or full URL',
                'type'        => 'text',
            ],
            'pinterest'  => [
                'icon'        => 'fab fa-pinterest',
                'name'        => 'Pinterest',
                'color'       => '#E60023',
                'prefix'      => 'https://pinterest.com/',
                'placeholder' => 'username',
                'hint'        => 'Enter Pinterest username or full URL',
                'type'        => 'text',
            ],
            'twitch'     => [
                'icon'        => 'fab fa-twitch',
                'name'        => 'Twitch',
                'color'       => '#9146FF',
                'prefix'      => 'https://twitch.tv/',
                'placeholder' => 'channel name',
                'hint'        => 'Enter Twitch channel name or full URL',
                'type'        => 'text',
            ],
            'discord'    => [
                'icon'        => 'fab fa-discord',
                'name'        => 'Discord',
                'color'       => '#5865F2',
                'prefix'      => 'https://discord.gg/',
                'placeholder' => 'invite-code or URL',
                'hint'        => 'Enter Discord server invite code or full URL',
                'type'        => 'text',
            ],
            'email'      => [
                'icon'        => 'fas fa-envelope',
                'name'        => 'Email',
                'color'       => '#EA4335',
                'prefix'      => 'mailto:',
                'placeholder' => 'yourname@example.com',
                'hint'        => 'Enter email address (automatically opens mail client)',
                'type'        => 'email',
            ],
            'portfolio'  => [
                'icon'        => 'fas fa-briefcase',
                'name'        => 'Portfolio',
                'color'       => '#38BDF8',
                'prefix'      => 'https://',
                'placeholder' => 'yourportfolio.com',
                'hint'        => 'Enter your portfolio link or domain (e.g. akeel.dev or full URL)',
                'type'        => 'url',
            ],
            'website'    => [
                'icon'        => 'fas fa-globe',
                'name'        => 'Website',
                'color'       => '#6D28D9',
                'prefix'      => 'https://',
                'placeholder' => 'https://example.com',
                'hint'        => 'Enter full website URL',
                'type'        => 'url',
            ],
            'custom'     => [
                'icon'        => 'fas fa-link',
                'name'        => 'Custom Link',
                'color'       => '#7C3AED',
                'prefix'      => 'https://',
                'placeholder' => 'https://...',
                'hint'        => 'Enter any web URL or link',
                'type'        => 'url',
            ],
        ];
    }

    /**
     * Format and clean user input into a proper destination URL.
     */
    public static function formatUrl(string $platform, string $input): string
    {
        $input = trim($input);
        $platforms = static::supportedPlatforms();
        $meta = $platforms[$platform] ?? null;

        if (!$meta) {
            return $input;
        }

        // Email special handling
        if ($platform === 'email') {
            $email = preg_replace('/^mailto:/i', '', $input);
            return 'mailto:' . trim($email);
        }

        // WhatsApp special handling
        if ($platform === 'whatsapp') {
            if (preg_match('/wa\.me\//i', $input) || preg_match('/api\.whatsapp\.com/i', $input)) {
                return str_starts_with($input, 'http') ? $input : 'https://' . ltrim($input, '/');
            }
            // Strip spaces, dashes, +, parentheses
            $cleanNumber = preg_replace('/[^0-9]/', '', $input);
            return 'https://wa.me/' . $cleanNumber;
        }

        // If user already typed http:// or https://, keep it
        if (str_starts_with($input, 'http://') || str_starts_with($input, 'https://')) {
            return $input;
        }

        // Discord invite special handling
        if ($platform === 'discord') {
            $code = str_replace(['discord.gg/', 'discord.com/invite/'], '', $input);
            return 'https://discord.gg/' . ltrim($code, '/');
        }

        // Platforms with standard prefix
        if (!empty($meta['prefix'])) {
            // Strip @ if user typed @username
            $cleanUser = ltrim($input, '@');
            // If user typed domain like instagram.com/user, prepend https://
            if (preg_match('/^[a-z0-9.-]+\.[a-z]{2,}/i', $cleanUser)) {
                return 'https://' . $cleanUser;
            }
            return $meta['prefix'] . $cleanUser;
        }

        // Default: add https:// if missing
        if (!str_starts_with($input, 'http://') && !str_starts_with($input, 'https://')) {
            return 'https://' . $input;
        }

        return $input;
    }

    /** Get icon class for this link's platform */
    public function iconClass(): string
    {
        return static::supportedPlatforms()[$this->platform]['icon'] ?? 'fas fa-link';
    }

    /** Get brand colour for this link's platform */
    public function brandColor(): string
    {
        return static::supportedPlatforms()[$this->platform]['color'] ?? '#7C3AED';
    }

    /** Increment click counter atomically */
    public function incrementClicks(): void
    {
        $this->increment('click_count');
    }
}
