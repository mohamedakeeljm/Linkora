<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Linkora — One Link to Share Everything</title>
    <meta name="description" content="Linkora is a modern personal link page builder. Collect all your social profiles, websites, and content in one beautiful, custom branded page.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Linkora CSS -->
    <link rel="stylesheet" href="{{ asset('css/linkora.css') }}">

    <style>
        .hero-section {
            min-height: 80vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 4rem 1.5rem 3rem;
            position: relative;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 1rem;
            background: rgba(139, 92, 246, 0.12);
            border: 1px solid rgba(139, 92, 246, 0.3);
            border-radius: var(--radius-full);
            color: var(--accent);
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 4.2rem);
            font-weight: 900;
            line-height: 1.15;
            letter-spacing: -0.03em;
            max-width: 820px;
            margin-bottom: 1.25rem;
        }

        .hero-subtitle {
            font-size: clamp(1rem, 2.5vw, 1.25rem);
            color: var(--text-secondary);
            max-width: 600px;
            line-height: 1.6;
            margin-bottom: 2.25rem;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 1100px;
            margin: 3rem auto 5rem;
            padding: 0 1.5rem;
        }

        .feature-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 2rem;
            transition: var(--transition-slow);
            backdrop-filter: blur(10px);
        }

        .feature-card:hover {
            transform: translateY(-4px);
            border-color: var(--border-hover);
            box-shadow: var(--shadow-purple);
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            border-radius: var(--radius);
            background: rgba(139, 92, 246, 0.15);
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 1.25rem;
        }

        .landing-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2.5rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        @media (max-width: 600px) {
            .landing-nav {
                padding: 1rem 1.25rem;
            }
        }
    </style>
</head>
<body>

    <!-- Top Navigation -->
    <header class="landing-nav">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div class="logo-icon"><i class="fas fa-link"></i></div>
            <span class="logo-text">Linkora</span>
        </div>

        <div style="display:flex; align-items:center; gap:1rem;">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-house"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Sign In</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-arrow-right"></i> Get Started
                </a>
            @endauth
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-badge animate-fade-up">
            <i class="fas fa-sparkles"></i> The Modern Link-in-Bio Alternative
        </div>

        <h1 class="hero-title animate-fade-up stagger-1">
            One Single Link for <span class="text-gradient">Everything You Create.</span>
        </h1>

        <p class="hero-subtitle animate-fade-up stagger-2">
            Build a sleek, high-converting personal landing page in minutes. Share your social accounts, stores, videos, and portfolio with custom styling and analytics.
        </p>

        <div style="display:flex; gap:1rem; flex-wrap:wrap; justify-content:center;" class="animate-fade-up stagger-3">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                    Go to Dashboard <i class="fas fa-arrow-right" style="margin-left:0.25rem;"></i>
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    Claim Your Link Free <i class="fas fa-arrow-right" style="margin-left:0.25rem;"></i>
                </a>
                <a href="{{ url('/demouser') }}" target="_blank" class="btn btn-secondary btn-lg">
                    <i class="fas fa-eye"></i> View Live Demo
                </a>
            @endauth
        </div>
    </section>

    <!-- Feature Grid -->
    <section class="feature-grid">
        <div class="feature-card animate-fade-up stagger-1">
            <div class="feature-icon"><i class="fas fa-palette"></i></div>
            <h3 style="margin-bottom:0.6rem;">Custom Themes & Styles</h3>
            <p style="font-size:0.9rem;">
                Select from sleek dark-mode presets or craft your own color palette with pill, glass, outline, or rounded button aesthetics.
            </p>
        </div>

        <div class="feature-card animate-fade-up stagger-2">
            <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
            <h3 style="margin-bottom:0.6rem;">Privacy-Safe Analytics</h3>
            <p style="font-size:0.9rem;">
                Track profile impressions, link clicks, conversion rates, and referrers over 7, 14, 30, or 90 days with zero intrusive tracking.
            </p>
        </div>

        <div class="feature-card animate-fade-up stagger-3">
            <div class="feature-icon"><i class="fas fa-qrcode"></i></div>
            <h3 style="margin-bottom:0.6rem;">Vector QR Generation</h3>
            <p style="font-size:0.9rem;">
                Instantly download branded SVG QR codes ready for physical stickers, posters, packaging, or business cards.
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer style="text-align:center; padding:2rem 1.5rem; border-top:1px solid var(--border); color:var(--text-muted); font-size:0.85rem;">
        <div>Linkora &copy; {{ date('Y') }} — Built with Laravel & Passion.</div>
    </footer>

</body>
</html>
