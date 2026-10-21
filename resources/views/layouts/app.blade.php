<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — Linkora</title>
    <meta name="description" content="@yield('meta_description', 'Manage your Linkora profile')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Linkora Design System -->
    <link rel="stylesheet" href="{{ asset('css/linkora.css') }}">

    @stack('styles')
</head>
<body>

{{-- Mobile sidebar backdrop --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="app-shell">

    {{-- ─── Sidebar ─────────────────────────────────────────── --}}
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-logo">
            <div class="logo-icon"><i class="fas fa-link"></i></div>
            <span class="logo-text">Linkora</span>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Main</div>

            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-house"></i><span>Dashboard</span>
            </a>

            <a href="{{ route('links.index') }}"
               class="nav-link {{ request()->routeIs('links.*') ? 'active' : '' }}">
                <i class="fas fa-link"></i><span>My Links</span>
            </a>

            <a href="{{ route('profile.edit') }}"
               class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <i class="fas fa-user-circle"></i><span>Profile</span>
            </a>

            <a href="{{ route('appearance.edit') }}"
               class="nav-link {{ request()->routeIs('appearance.*') ? 'active' : '' }}">
                <i class="fas fa-palette"></i><span>Appearance</span>
            </a>

            <div class="nav-section-label" style="margin-top:.5rem;">Insights</div>

            <a href="{{ route('analytics.index') }}"
               class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i><span>Analytics</span>
            </a>

            <a href="{{ route('qrcode.show') }}"
               class="nav-link {{ request()->routeIs('qrcode.*') ? 'active' : '' }}">
                <i class="fas fa-qrcode"></i><span>QR Code</span>
            </a>

            <div class="nav-section-label" style="margin-top:.5rem;">Account</div>

            @if(auth()->user()->username)
            <a href="{{ route('profile.show', auth()->user()->username) }}"
               target="_blank" rel="noopener"
               class="nav-link">
                <i class="fas fa-arrow-up-right-from-square"></i><span>View Profile</span>
            </a>
            @endif

            <a href="{{ route('settings.edit') }}"
               class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="fas fa-gear"></i><span>Settings</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link" style="color:var(--danger);">
                    <i class="fas fa-right-from-bracket"></i><span>Logout</span>
                </button>
            </form>
        </nav>

        <div class="sidebar-user">
            <img src="{{ auth()->user()->avatarUrl() }}" alt="avatar" class="sidebar-avatar">
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-handle">
                    {{ auth()->user()->username ? '@'.auth()->user()->username : 'No username set' }}
                </div>
            </div>
        </div>
    </aside>

    {{-- ─── Main ────────────────────────────────────────────── --}}
    <main class="main-content">

        {{-- Mobile header --}}
        <div style="display:none;align-items:center;gap:1rem;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border);" id="mobileHeader">
            <button class="hamburger" id="hamburgerBtn" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>
            <span class="logo-text" style="font-size:1.2rem;">Linkora</span>
        </div>

        {{-- Session alerts --}}
        @if(session('success'))
        <div class="alert alert-success animate-fade-in">
            <i class="fas fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger animate-fade-in">
            <i class="fas fa-triangle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        @yield('content')
    </main>

</div>

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Mobile sidebar
const sidebar   = document.getElementById('sidebar');
const overlay   = document.getElementById('sidebarOverlay');
const hamburger = document.getElementById('hamburgerBtn');
const mobileHdr = document.getElementById('mobileHeader');

function checkViewport() {
    const mobile = window.innerWidth <= 900;
    if (mobileHdr) mobileHdr.style.display = mobile ? 'flex' : 'none';
}
checkViewport();
window.addEventListener('resize', checkViewport);

hamburger?.addEventListener('click', () => { sidebar.classList.add('open'); overlay.classList.add('active'); });
overlay?.addEventListener('click', () => { sidebar.classList.remove('open'); overlay.classList.remove('active'); });

// Auto-dismiss alerts
document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => { el.style.transition = 'opacity .5s'; el.style.opacity = '0'; setTimeout(() => el.remove(), 500); }, 4500);
});

// Global copy helper
window.copyToClipboard = function(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        btn.classList.add('btn-success');
        setTimeout(() => { btn.innerHTML = orig; btn.classList.remove('btn-success'); }, 2200);
    }).catch(() => alert('Could not copy. Please copy manually: ' + text));
};
</script>

@stack('scripts')
</body>
</html>
