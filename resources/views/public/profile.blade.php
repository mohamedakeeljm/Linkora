<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $user->name }} (@{{ $user->username }}) — Linkora</title>
    <meta name="description" content="{{ $user->bio ?: 'Check out my links on Linkora!' }}">

    <!-- OpenGraph / Social Sharing -->
    <meta property="og:title" content="{{ $user->name }} (@{{ $user->username }})">
    <meta property="og:description" content="{{ $user->bio ?: 'Check out my links on Linkora!' }}">
    <meta property="og:image" content="{{ $user->avatarUrl() }}">
    <meta property="og:type" content="profile">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $user->name }} (@{{ $user->username }})">
    <meta name="twitter:description" content="{{ $user->bio ?: 'Check out my links on Linkora!' }}">
    <meta name="twitter:image" content="{{ $user->avatarUrl() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @php
        // Determine theme & custom color overrides
        $theme = $settings->theme ?? 'default';
        $bgCustom = $settings->bg_color;
        $accentCustom = $settings->accent_color;
        $btnStyle = $settings->button_style ?? 'pill';
        $layout = $settings->layout ?? 'centered';

        // Theme presets
        $themeStyles = [
            'default'  => ['bg' => '#0a0a0f', 'card' => 'rgba(255, 255, 255, 0.05)', 'accent' => '#8b5cf6', 'text' => '#f1f0ff'],
            'ocean'    => ['bg' => '#07111e', 'card' => 'rgba(14, 165, 233, 0.08)', 'accent' => '#0ea5e9', 'text' => '#f0f9ff'],
            'sunset'   => ['bg' => '#1a0b12', 'card' => 'rgba(244, 63, 94, 0.08)',  'accent' => '#f43f5e', 'text' => '#fff1f2'],
            'forest'   => ['bg' => '#08140e', 'card' => 'rgba(16, 185, 129, 0.08)', 'accent' => '#10b981', 'text' => '#ecfdf5'],
            'midnight' => ['bg' => '#030712', 'card' => 'rgba(99, 102, 241, 0.08)', 'accent' => '#6366f1', 'text' => '#f8fafc'],
            'rose'     => ['bg' => '#17060f', 'card' => 'rgba(236, 72, 153, 0.08)', 'accent' => '#ec4899', 'text' => '#fdf2f8'],
        ];

        $currentTheme = $themeStyles[$theme] ?? $themeStyles['default'];
        $bgColor = $bgCustom ?: $currentTheme['bg'];
        $accentColor = $accentCustom ?: $currentTheme['accent'];
    @endphp

    <style>
        :root {
            --profile-bg: {{ $bgColor }};
            --profile-card: {{ $currentTheme['card'] }};
            --profile-accent: {{ $accentColor }};
            --profile-text: {{ $currentTheme['text'] }};
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--profile-bg);
            color: var(--profile-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 3rem 1.25rem 2rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Subtle ambient glow */
        body::before {
            content: '';
            position: fixed;
            top: -20%;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, var(--profile-accent) 0%, transparent 70%);
            opacity: 0.15;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }

        .profile-container {
            width: 100%;
            max-width: 580px;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: {{ $layout === 'left' ? 'flex-start' : 'center' }};
            text-align: {{ $layout === 'left' ? 'left' : 'center' }};
        }

        /* Profile Header */
        .avatar-wrap {
            margin-bottom: 1.25rem;
            position: relative;
        }

        .profile-avatar {
            width: 104px;
            height: 104px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--profile-accent);
            box-shadow: 0 0 25px rgba(0,0,0,0.5), 0 0 15px var(--profile-accent);
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 0.25rem;
        }

        .profile-username {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--profile-accent);
            margin-bottom: 0.75rem;
        }

        .profile-bio {
            font-size: 0.95rem;
            line-height: 1.5;
            opacity: 0.85;
            margin-bottom: 1.5rem;
            max-width: 460px;
        }

        .profile-website {
            font-size: 0.85rem;
            margin-bottom: 2rem;
            opacity: 0.75;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            color: inherit;
            transition: opacity 0.2s;
        }

        .profile-website:hover {
            opacity: 1;
            color: var(--profile-accent);
        }

        /* Links List */
        .links-container {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        /* Button style variants */
        .link-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: {{ $layout === 'compact' ? '0.75rem 1.25rem' : '1.1rem 1.5rem' }};
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            color: var(--profile-text);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            cursor: pointer;
        }

        /* Styles */
        @if($btnStyle === 'pill')
            .link-btn {
                border-radius: 9999px;
                background: var(--profile-card);
                border: 1px solid rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(12px);
            }
            .link-btn:hover {
                transform: translateY(-2px) scale(1.01);
                border-color: var(--profile-accent);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4), 0 0 16px var(--profile-accent);
            }
        @elseif($btnStyle === 'rounded')
            .link-btn {
                border-radius: 12px;
                background: var(--profile-card);
                border: 1px solid rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(12px);
            }
            .link-btn:hover {
                transform: translateY(-2px);
                border-color: var(--profile-accent);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4), 0 0 15px var(--profile-accent);
            }
        @elseif($btnStyle === 'sharp')
            .link-btn {
                border-radius: 0px;
                background: var(--profile-card);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .link-btn:hover {
                transform: translateY(-2px);
                border-color: var(--profile-accent);
                box-shadow: 0 4px 15px rgba(0,0,0,0.5);
            }
        @elseif($btnStyle === 'outline')
            .link-btn {
                border-radius: 9999px;
                background: transparent;
                border: 2px solid var(--profile-accent);
            }
            .link-btn:hover {
                background: var(--profile-accent);
                color: #fff;
                transform: translateY(-2px);
                box-shadow: 0 0 20px var(--profile-accent);
            }
        @elseif($btnStyle === 'glass')
            .link-btn {
                border-radius: 16px;
                background: rgba(255, 255, 255, 0.04);
                border: 1px solid rgba(255, 255, 255, 0.12);
                backdrop-filter: blur(25px);
            }
            .link-btn:hover {
                background: rgba(255, 255, 255, 0.08);
                border-color: var(--profile-accent);
                transform: translateY(-2px);
            }
        @endif

        .link-icon-wrap {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            border-radius: 10px;
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .link-btn:hover .link-icon-wrap {
            transform: scale(1.15);
        }

        .link-btn-label {
            flex: 1;
            text-align: center;
            padding: 0 0.5rem;
        }

        .link-arrow {
            opacity: 0.4;
            font-size: 0.8rem;
            transition: transform 0.2s, opacity 0.2s;
        }

        .link-btn:hover .link-arrow {
            opacity: 1;
            transform: translateX(3px);
            color: var(--profile-accent);
        }

        /* Branding Footer */
        .footer-brand {
            margin-top: auto;
            font-size: 0.8rem;
            opacity: 0.6;
            text-decoration: none;
            color: inherit;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: opacity 0.2s;
            z-index: 1;
        }

        .footer-brand:hover {
            opacity: 1;
        }

        .brand-badge {
            background: linear-gradient(135deg, #7c3aed, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <!-- Avatar -->
    <div class="avatar-wrap">
        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="profile-avatar">
    </div>

    <!-- Header Details -->
    <h1 class="profile-name">{{ $user->name }}</h1>
    <div class="profile-username">&#64;{{ $user->username }}</div>

    @if($user->bio)
        <p class="profile-bio">{{ $user->bio }}</p>
    @endif

    @if($user->website)
        <a href="{{ $user->website }}" target="_blank" rel="noopener" class="profile-website">
            <i class="fas fa-globe"></i> {{ parse_url($user->website, PHP_URL_HOST) ?? $user->website }}
        </a>
    @endif

    <!-- Links List -->
    <div class="links-container">
        @forelse($links as $link)
            <a href="{{ route('profile.click', ['username' => $user->username, 'link' => $link->id]) }}"
               target="_blank"
               rel="noopener noreferrer"
               class="link-btn">
                <div class="link-icon-wrap" style="color: {{ $link->brandColor() }};">
                    <i class="{{ $link->iconClass() }}"></i>
                </div>
                <span class="link-btn-label">{{ $link->label }}</span>
                <i class="fas fa-arrow-right link-arrow"></i>
            </a>
        @empty
            <div style="opacity: 0.5; padding: 2rem; font-size: 0.9rem;">
                No public links available yet.
            </div>
        @endforelse
    </div>
</div>

<a href="{{ url('/') }}" class="footer-brand" target="_blank">
    <span>Powered by</span>
    <span class="brand-badge"><i class="fas fa-link"></i> Linkora</span>
</a>

</body>
</html>
