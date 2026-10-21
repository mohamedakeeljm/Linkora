@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

{{-- Page header --}}
<div class="topbar animate-fade-up">
    <div class="topbar-title">
        <h1>Welcome back, <span class="text-gradient">{{ auth()->user()->name }}</span> 👋</h1>
        <p>Here's what's happening with your profile today.</p>
    </div>
    <div class="topbar-actions">
        @if(auth()->user()->username)
        <a href="{{ route('profile.show', auth()->user()->username) }}"
           target="_blank" rel="noopener" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-up-right-from-square"></i> View Profile
        </a>
        @endif
        <a href="{{ route('links.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Link
        </a>
    </div>
</div>

{{-- Username setup notice --}}
@if(!auth()->user()->username)
<div class="alert alert-warning animate-fade-up" style="animation-delay:.05s;">
    <i class="fas fa-triangle-exclamation"></i>
    <div>
        <strong>One more step!</strong> Set your username to get your shareable profile URL.
        <a href="{{ route('profile.edit') }}" style="color:var(--warning);text-decoration:underline;margin-left:.5rem;">
            Set username →
        </a>
    </div>
</div>
@endif

{{-- ─── Stats Row ───────────────────────────────────────────── --}}
<div class="stats-grid">

    <div class="stat-card animate-fade-up stagger-1">
        <div class="stat-icon stat-icon-purple"><i class="fas fa-eye"></i></div>
        <div>
            <div class="stat-value">{{ number_format($totalViews) }}</div>
            <div class="stat-label">Profile Views</div>
            <div class="stat-change up"><i class="fas fa-arrow-trend-up"></i> {{ $viewsToday }} today</div>
        </div>
    </div>

    <div class="stat-card animate-fade-up stagger-2">
        <div class="stat-icon stat-icon-pink"><i class="fas fa-hand-pointer"></i></div>
        <div>
            <div class="stat-value">{{ number_format($totalClicks) }}</div>
            <div class="stat-label">Link Clicks</div>
            <div class="stat-change up"><i class="fas fa-arrow-trend-up"></i> {{ $clicksToday }} today</div>
        </div>
    </div>

    <div class="stat-card animate-fade-up stagger-3">
        <div class="stat-icon stat-icon-cyan"><i class="fas fa-link"></i></div>
        <div>
            <div class="stat-value">{{ $totalLinks }}</div>
            <div class="stat-label">Active Links</div>
            <div class="stat-change" style="color:var(--text-muted);">{{ $totalLinksAll }} total</div>
        </div>
    </div>

    <div class="stat-card animate-fade-up stagger-4">
        <div class="stat-icon stat-icon-green"><i class="fas fa-percent"></i></div>
        <div>
            <div class="stat-value">{{ $ctr }}%</div>
            <div class="stat-label">Click-through Rate</div>
            <div class="stat-change" style="color:var(--text-muted);">clicks ÷ views</div>
        </div>
    </div>

</div>

{{-- ─── Dashboard Grid ──────────────────────────────────────── --}}
<div class="dashboard-grid">

    {{-- ── Left column ── --}}
    <div style="display:flex;flex-direction:column;gap:1.5rem;">

        {{-- Profile URL card --}}
        <div class="card animate-fade-up" style="animation-delay:.15s;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                <h4 style="margin:0;">Your Profile URL</h4>
                @if(auth()->user()->username)
                    <span class="badge badge-green"><i class="fas fa-circle" style="font-size:.45rem;"></i> Live</span>
                @else
                    <span class="badge badge-gray">Not configured</span>
                @endif
            </div>

            @if(auth()->user()->username)
            <div class="profile-url-box">
                <span class="profile-url-text">{{ route('profile.show', auth()->user()->username) }}</span>
                <button class="btn btn-primary btn-sm"
                        onclick="copyToClipboard('{{ route('profile.show', auth()->user()->username) }}', this)">
                    <i class="fas fa-copy"></i> Copy
                </button>
            </div>
            @else
            <div class="empty-state" style="padding:1.5rem 1rem;">
                <div style="font-size:2rem;margin-bottom:.5rem;">🔗</div>
                <p style="margin-bottom:1rem;font-size:.875rem;">Set a username to get your public link.</p>
                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-pen"></i> Set Username
                </a>
            </div>
            @endif
        </div>

        {{-- 7-day views chart --}}
        <div class="card animate-fade-up" style="animation-delay:.2s;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
                <h4 style="margin:0;">Views — Last 7 Days</h4>
                <a href="{{ route('analytics.index') }}" class="btn btn-ghost btn-sm">Full report</a>
            </div>
            <div class="chart-container"><canvas id="viewsChart"></canvas></div>
        </div>

        {{-- Top links table --}}
        <div class="card animate-fade-up" style="animation-delay:.25s;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
                <h4 style="margin:0;">Top Performing Links</h4>
                <a href="{{ route('links.index') }}" class="btn btn-ghost btn-sm">Manage</a>
            </div>

            @if($topLinks->isEmpty())
            <div class="empty-state" style="padding:1.5rem;">
                <div class="empty-state-icon" style="font-size:2rem;margin-bottom:.5rem;">🔗</div>
                <h3 style="font-size:1rem;">No links added yet</h3>
                <p style="font-size:.825rem;">Add your first social link to start tracking clicks.</p>
                <a href="{{ route('links.create') }}" class="btn btn-primary btn-sm" style="margin-top:1rem;">
                    <i class="fas fa-plus"></i> Add First Link
                </a>
            </div>
            @else
            <div style="display:flex;flex-direction:column;gap:.875rem;">
                @foreach($topLinks as $i => $link)
                <div style="display:flex;align-items:center;gap:.875rem;">
                    <span style="width:20px;text-align:center;font-size:.75rem;font-weight:700;color:var(--text-muted);">{{ $i+1 }}</span>
                    <div style="width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:{{ $link->brandColor() }}22;color:{{ $link->brandColor() }};flex-shrink:0;">
                        <i class="{{ $link->iconClass() }}"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:.875rem;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $link->label }}</div>
                        <div style="font-size:.75rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $link->url }}</div>
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        <div style="font-size:.925rem;font-weight:800;color:var(--accent);">{{ number_format($link->click_count) }}</div>
                        <div style="font-size:.68rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;">clicks</div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>

    {{-- ── Right column ── --}}
    <div style="display:flex;flex-direction:column;gap:1.5rem;">

        {{-- Profile preview card --}}
        <div class="card card-purple animate-fade-up" style="animation-delay:.1s;text-align:center;">
            <img src="{{ auth()->user()->avatarUrl() }}"
                 alt="{{ auth()->user()->name }}"
                 style="width:76px;height:76px;border-radius:50%;object-fit:cover;margin:0 auto .875rem;border:3px solid var(--border-hover);">
            <div style="font-weight:700;font-size:1.05rem;color:var(--text-primary);">{{ auth()->user()->name }}</div>
            @if(auth()->user()->username)
            <div style="font-size:.825rem;color:var(--accent);margin-top:.2rem;">@{{ auth()->user()->username }}</div>
            @endif
            @if(auth()->user()->bio)
            <p style="font-size:.8rem;margin-top:.625rem;color:var(--text-secondary);">{{ Str::limit(auth()->user()->bio, 90) }}</p>
            @endif
            <a href="{{ route('profile.edit') }}" class="btn btn-secondary w-full btn-sm" style="margin-top:1rem;">
                <i class="fas fa-pen"></i> Edit Profile
            </a>
        </div>

        {{-- Quick actions --}}
        <div class="card animate-fade-up" style="animation-delay:.2s;">
            <h4 style="margin-bottom:1rem;">Quick Actions</h4>
            <div style="display:flex;flex-direction:column;gap:.625rem;">
                <a href="{{ route('links.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Link
                </a>
                <a href="{{ route('appearance.edit') }}" class="btn btn-secondary">
                    <i class="fas fa-palette"></i> Customize Appearance
                </a>
                <a href="{{ route('qrcode.show') }}" class="btn btn-secondary">
                    <i class="fas fa-qrcode"></i> Get QR Code
                </a>
                @if(auth()->user()->username)
                <a href="{{ route('profile.show', auth()->user()->username) }}"
                   target="_blank" class="btn btn-ghost">
                    <i class="fas fa-eye"></i> Preview Public Page
                </a>
                @endif
            </div>
        </div>

        {{-- Links mini summary --}}
        <div class="card animate-fade-up" style="animation-delay:.25s;">
            <h4 style="margin-bottom:1rem;">Links Summary</h4>
            <div style="display:flex;justify-content:space-around;text-align:center;padding:.5rem 0;">
                <div>
                    <div style="font-size:1.8rem;font-weight:800;color:var(--success);">{{ $totalLinks }}</div>
                    <div style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;margin-top:.25rem;">Active</div>
                </div>
                <div style="width:1px;background:var(--border);"></div>
                <div>
                    <div style="font-size:1.8rem;font-weight:800;color:var(--danger);">{{ $totalLinksAll - $totalLinks }}</div>
                    <div style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;margin-top:.25rem;">Hidden</div>
                </div>
                <div style="width:1px;background:var(--border);"></div>
                <div>
                    <div style="font-size:1.8rem;font-weight:800;color:var(--accent);">{{ $totalLinksAll }}</div>
                    <div style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;margin-top:.25rem;">Total</div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
const ctx = document.getElementById('viewsChart');
if (ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Views',
                data: @json($chartData),
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139,92,246,0.1)',
                borderWidth: 2.5,
                pointBackgroundColor: '#8b5cf6',
                pointBorderColor: '#0a0a0f',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a1a2e',
                    borderColor: 'rgba(139,92,246,0.3)',
                    borderWidth: 1,
                    titleColor: '#f1f0ff',
                    bodyColor: '#a09ac0',
                    padding: 10,
                }
            },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#6b6589', font: { size: 11 } } },
                y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#6b6589', font: { size: 11 }, precision: 0 }, beginAtZero: true }
            }
        }
    });
}
</script>
@endpush
