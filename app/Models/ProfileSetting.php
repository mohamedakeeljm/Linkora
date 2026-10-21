<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'theme',
        'bg_color',
        'accent_color',
        'button_style',
        'layout',
    ];

    /**
     * Available preset themes with their CSS variable values.
     * Each theme defines bg, card, accent, and text colours.
     */
    public static function themes(): array
    {
        return [
            'default'  => ['name' => 'Purple Night',  'preview' => '#0a0a0f'],
            'ocean'    => ['name' => 'Deep Ocean',     'preview' => '#0a1628'],
            'sunset'   => ['name' => 'Sunset Vibes',   'preview' => '#1a0a0a'],
            'forest'   => ['name' => 'Dark Forest',    'preview' => '#0a1a0a'],
            'midnight' => ['name' => 'Midnight Blue',  'preview' => '#0a0a1a'],
            'rose'     => ['name' => 'Rose Gold',      'preview' => '#1a0a10'],
        ];
    }

    /** Button style options */
    public static function buttonStyles(): array
    {
        return ['pill', 'rounded', 'sharp', 'outline', 'glass'];
    }

    /** Layout options */
    public static function layouts(): array
    {
        return ['centered', 'left', 'compact'];
    }

    // ─── Relationships ───────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
